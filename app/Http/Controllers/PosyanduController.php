<?php

namespace App\Http\Controllers;

use App\Models\Posyandu;
use App\Models\User;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PosyanduExport;

class PosyanduController extends Controller
{
    private function authorizeAdmin()
    {
        if (auth()->check() && auth()->user()->hasRole('posyandu')) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin();
        $query = Posyandu::query()->with('users')->withCount('kaders');
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('alamat', 'like', '%' . $request->search . '%');
            });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $posyandus = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();
        
        return view('posyandus.index', compact('posyandus'));
    }

    public function create()
    {
        $dusuns = Penduduk::distinct()->whereNotNull('dusun')->where('dusun', '!=', '')->orderBy('dusun')->pluck('dusun');
        return view('posyandus.create', compact('dusuns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'ketua' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'telepon' => 'nullable|string|max:20',
            'dusun' => 'required|string|max:255',
            'rw_diampu' => 'required|array',
            'rw_diampu.*' => 'string',
            'jadwal_rutin' => 'nullable|string|max:255',
            'lokasi_koordinat' => 'nullable|string|max:255',
            'bangunan' => 'nullable|string|max:100',
            'punya_timbangan_dacin' => 'nullable|boolean',
            'punya_timbangan_digital' => 'nullable|boolean',
            'punya_alat_ukur_tinggi' => 'nullable|boolean',
            'punya_pita_liLa' => 'nullable|boolean',
            'punya_buku_kia' => 'nullable|boolean',
            'keterangan_lain' => 'nullable|string',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $posyandu = Posyandu::create([
            'nama' => $validated['nama'],
            'ketua' => $validated['ketua'],
            'alamat' => $validated['alamat'],
            'telepon' => $validated['telepon'],
            'dusun' => $validated['dusun'],
            'rw_diampu' => $validated['rw_diampu'],
            'jadwal_rutin' => $validated['jadwal_rutin'],
            'lokasi_koordinat' => $validated['lokasi_koordinat'],
            'bangunan' => $validated['bangunan'],
            'punya_timbangan_dacin' => $request->has('punya_timbangan_dacin'),
            'punya_timbangan_digital' => $request->has('punya_timbangan_digital'),
            'punya_alat_ukur_tinggi' => $request->has('punya_alat_ukur_tinggi'),
            'punya_pita_liLa' => $request->has('punya_pita_liLa'),
            'punya_buku_kia' => $request->has('punya_buku_kia'),
            'keterangan_lain' => $validated['keterangan_lain'],
        ]);

        $user = User::create([
            'name' => $validated['nama'],
            'username' => $validated['username'],
            'role' => 'posyandu',
            'password' => Hash::make($validated['password']),
            'posyandu_id' => $posyandu->id,
        ]);

        $user->assignRole('posyandu');

        return redirect()->route('posyandus.index')->with('success', 'Data Posyandu dan Akun berhasil ditambahkan');
    }

    public function edit(Posyandu $posyandu)
    {
        $posyandu->load('users');
        $user = $posyandu->users->first();
        $dusuns = Penduduk::distinct()->whereNotNull('dusun')->where('dusun', '!=', '')->orderBy('dusun')->pluck('dusun');
        
        $rws = [];
        if ($posyandu->dusun) {
            $rws = Penduduk::where('dusun', $posyandu->dusun)
                ->distinct()
                ->whereNotNull('rw')
                ->where('rw', '!=', '')
                ->orderByRaw('CAST(rw AS UNSIGNED) ASC')
                ->pluck('rw');
        }

        return view('posyandus.edit', compact('posyandu', 'user', 'dusuns', 'rws'));
    }

    public function update(Request $request, Posyandu $posyandu)
    {
        $user = $posyandu->users->first();
        
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'ketua' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'telepon' => 'nullable|string|max:20',
            'dusun' => 'required|string|max:255',
            'rw_diampu' => 'required|array',
            'rw_diampu.*' => 'string',
            'jadwal_rutin' => 'nullable|string|max:255',
            'lokasi_koordinat' => 'nullable|string|max:255',
            'bangunan' => 'nullable|string|max:100',
            'punya_timbangan_dacin' => 'nullable|boolean',
            'punya_timbangan_digital' => 'nullable|boolean',
            'punya_alat_ukur_tinggi' => 'nullable|boolean',
            'punya_pita_liLa' => 'nullable|boolean',
            'punya_buku_kia' => 'nullable|boolean',
            'keterangan_lain' => 'nullable|string',
            'username' => 'required|string|max:255|unique:users,username,' . ($user ? $user->id : 'NULL'),
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $posyandu->update([
            'nama' => $validated['nama'],
            'ketua' => $validated['ketua'],
            'alamat' => $validated['alamat'],
            'telepon' => $validated['telepon'],
            'dusun' => $validated['dusun'],
            'rw_diampu' => $validated['rw_diampu'],
            'jadwal_rutin' => $validated['jadwal_rutin'],
            'lokasi_koordinat' => $validated['lokasi_koordinat'],
            'bangunan' => $validated['bangunan'],
            'punya_timbangan_dacin' => $request->has('punya_timbangan_dacin'),
            'punya_timbangan_digital' => $request->has('punya_timbangan_digital'),
            'punya_alat_ukur_tinggi' => $request->has('punya_alat_ukur_tinggi'),
            'punya_pita_liLa' => $request->has('punya_pita_liLa'),
            'punya_buku_kia' => $request->has('punya_buku_kia'),
            'keterangan_lain' => $validated['keterangan_lain'],
        ]);

        if ($user) {
            $userData = [
                'name' => $validated['nama'],
                'username' => $validated['username'],
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($validated['password']);
            }
            $user->update($userData);
        } else {
            $user = User::create([
                'name' => $validated['nama'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password'] ?? 'password'),
                'posyandu_id' => $posyandu->id,
            ]);
            $user->assignRole('posyandu');
        }

        return redirect()->route('posyandus.index')->with('success', 'Data berhasil diubah');
    }

    public function getRws(Request $request)
    {
        $rws = Penduduk::where('dusun', $request->dusun)
            ->distinct()
            ->whereNotNull('rw')
            ->where('rw', '!=', '')
            ->orderByRaw('CAST(rw AS UNSIGNED) ASC')
            ->pluck('rw');
            
        return response()->json($rws);
    }

    public function destroy(Posyandu $posyandu)
    {
        $posyandu->delete();
        return redirect()->route('posyandus.index')->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        return Excel::download(new PosyanduExport($request->all()), 'posyandus.xlsx');
    }
}
