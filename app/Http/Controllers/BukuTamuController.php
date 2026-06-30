<?php

namespace App\Http\Controllers;

use App\Models\BukuTamu;
use App\Models\Posyandu;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BukuTamuExport;

class BukuTamuController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = BukuTamu::with('posyandu');
        
        // Scope to posyandu if the logged-in user belongs to one
        if ($user && $user->hasRole('posyandu') && $user->posyandu_id) {
            $query->where('posyandu_id', $user->posyandu_id);
        }
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('jabatan', 'like', '%' . $request->search . '%')
                  ->orWhere('alamat', 'like', '%' . $request->search . '%')
                  ->orWhere('keperluan', 'like', '%' . $request->search . '%');
            });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $bukuTamus = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();
        
        return view('buku-tamus.index', compact('bukuTamus'));
    }

    public function create()
    {
        $user = auth()->user();
        $posyandus = [];
        
        if (!($user && $user->hasRole('posyandu') && $user->posyandu_id)) {
            $posyandus = Posyandu::orderBy('nama')->get();
        }
        
        return view('buku-tamus.create', compact('posyandus'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $isPosyanduUser = $user && $user->hasRole('posyandu') && $user->posyandu_id;
        
        $rules = [
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'keperluan' => 'required|string|max:255',
            'tanggal_kunjungan' => 'required|date',
            'keterangan' => 'nullable|string',
        ];
        
        if (!$isPosyanduUser) {
            $rules['posyandu_id'] = 'nullable|exists:posyandus,id';
        }
        
        $validated = $request->validate($rules);
        
        $validated['jam_masuk'] = now()->format('H:i:s');
        
        if ($isPosyanduUser) {
            $validated['posyandu_id'] = $user->posyandu_id;
        }
        
        BukuTamu::create($validated);
        return redirect()->route('buku-tamus.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(BukuTamu $bukuTamu)
    {
        $user = auth()->user();
        
        // Prevent editing guest books of other posyandu if this is a posyandu account
        if ($user && $user->hasRole('posyandu') && $user->posyandu_id) {
            if ($bukuTamu->posyandu_id != $user->posyandu_id) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        $posyandus = [];
        if (!($user && $user->hasRole('posyandu') && $user->posyandu_id)) {
            $posyandus = Posyandu::orderBy('nama')->get();
        }
        
        return view('buku-tamus.edit', compact('bukuTamu', 'posyandus'));
    }

    public function update(Request $request, BukuTamu $bukuTamu)
    {
        $user = auth()->user();
        $isPosyanduUser = $user && $user->hasRole('posyandu') && $user->posyandu_id;
        
        if ($isPosyanduUser) {
            if ($bukuTamu->posyandu_id != $user->posyandu_id) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        $rules = [
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'keperluan' => 'required|string|max:255',
            'tanggal_kunjungan' => 'required|date',
            'keterangan' => 'nullable|string',
        ];
        
        if (!$isPosyanduUser) {
            $rules['posyandu_id'] = 'nullable|exists:posyandus,id';
        }
        
        $validated = $request->validate($rules);
        
        if ($isPosyanduUser) {
            $validated['posyandu_id'] = $user->posyandu_id;
        }
        
        $bukuTamu->update($validated);
        return redirect()->route('buku-tamus.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy(BukuTamu $bukuTamu)
    {
        $user = auth()->user();
        if ($user && $user->hasRole('posyandu') && $user->posyandu_id) {
            if ($bukuTamu->posyandu_id != $user->posyandu_id) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        $bukuTamu->delete();
        return redirect()->route('buku-tamus.index')->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        $user = auth()->user();
        if ($user && $user->hasRole('posyandu') && $user->posyandu_id) {
            $filters['posyandu_id'] = $user->posyandu_id;
        }
        return Excel::download(new BukuTamuExport($filters), 'buku-tamus.xlsx');
    }
}
