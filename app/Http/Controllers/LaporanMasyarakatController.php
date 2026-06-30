<?php

namespace App\Http\Controllers;

use App\Models\LaporanMasyarakat;
use App\Models\Posyandu;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanMasyarakatExport;
use App\Traits\HasHierarchicalFilter;

class LaporanMasyarakatController extends Controller
{
    use HasHierarchicalFilter;

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = LaporanMasyarakat::with('posyandu')
            ->leftJoin('penduduks', 'laporan_masyarakats.nik_pelapor', '=', 'penduduks.nik')
            ->select('laporan_masyarakats.*');
            
        // Scope to posyandu if the logged-in user belongs to one
        if ($user && $user->hasRole('posyandu') && $user->posyandu_id) {
            $query->where('laporan_masyarakats.posyandu_id', $user->posyandu_id);
        }
            
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama_pelapor', 'like', '%' . $request->search . '%')
                  ->orWhere('nik_pelapor', 'like', '%' . $request->search . '%')
                  ->orWhere('isi_laporan', 'like', '%' . $request->search . '%');
            });
        }

        $query = $this->applyHierarchicalFilters($query, $request, 'penduduks');

        $sortField = $request->get('sort', 'laporan_masyarakats.created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $laporanMasyarakats = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();

        $posyandus = [];
        if (!($user && $user->hasRole('posyandu') && $user->posyandu_id)) {
            $posyandus = Posyandu::orderBy('nama')->get();
        }
 
        $kategories = [
            'Bidang Pendidikan',
            'Bidang Pekerjaan Umum',
            'Bidang Perumahan Rakyat',
            'Bidang Trantibum Linmas',
            'Bidang Sosial',
        ];
 
        $filters = $this->getHierarchicalFilterOptions($request);
        return view('laporan-masyarakats.index', array_merge(compact('laporanMasyarakats', 'posyandus', 'kategories'), $filters));
    }

    public function create()
    {
        $user = auth()->user();
        $posyandus = [];
        if (!($user && $user->hasRole('posyandu') && $user->posyandu_id)) {
            $posyandus = Posyandu::orderBy('nama')->get();
        }
        return view('laporan-masyarakats.create', compact('posyandus'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $isPosyanduUser = $user && $user->hasRole('posyandu') && $user->posyandu_id;
        
        $rules = [
            'hari_tanggal' => 'required|date',
            'nama_pelapor' => 'required|string|max:255',
            'nik_pelapor' => 'required|string|size:16',
            'no_kk' => 'required|string|size:16',
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'required|string',
            'isi_laporan' => 'required|string',
            'kategori' => 'nullable|string|max:100',
            'foto_bukti' => 'nullable|image|max:2048',
            'status' => 'required|in:baru,diproses,selesai,ditolak',
        ];

        if (!$isPosyanduUser) {
            $rules['posyandu_id'] = 'nullable|exists:posyandus,id';
        }

        $validated = $request->validate($rules, [
            'nik_pelapor.size' => 'NIK harus berjumlah 16 digit.',
            'no_kk.required' => 'No. KK wajib diisi.',
            'no_kk.size' => 'No. KK harus berjumlah 16 digit.',
        ]);

        if ($request->hasFile('foto_bukti')) {
            $validated['foto_bukti'] = $request->file('foto_bukti')->store('laporan', 'public');
        }

        if ($isPosyanduUser) {
            $validated['posyandu_id'] = $user->posyandu_id;
        }

        LaporanMasyarakat::create($validated);
        return redirect()->route('laporan-masyarakats.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(LaporanMasyarakat $laporanMasyarakat)
    {
        $user = auth()->user();
        if ($user && $user->hasRole('posyandu') && $user->posyandu_id) {
            if ($laporanMasyarakat->posyandu_id != $user->posyandu_id) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        $posyandus = [];
        if (!($user && $user->hasRole('posyandu') && $user->posyandu_id)) {
            $posyandus = Posyandu::orderBy('nama')->get();
        }
        return view('laporan-masyarakats.edit', compact('laporanMasyarakat', 'posyandus'));
    }

    public function update(Request $request, LaporanMasyarakat $laporanMasyarakat)
    {
        $user = auth()->user();
        $isPosyanduUser = $user && $user->hasRole('posyandu') && $user->posyandu_id;
        
        if ($isPosyanduUser) {
            if ($laporanMasyarakat->posyandu_id != $user->posyandu_id) {
                abort(403, 'Unauthorized action.');
            }
        }

        $validated = $request->validate([
            'status' => 'required|in:baru,diproses,selesai,ditolak',
            'balasan' => 'nullable|string',
        ]);

        $laporanMasyarakat->update($validated);
        return redirect()->route('laporan-masyarakats.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy(LaporanMasyarakat $laporanMasyarakat)
    {
        $user = auth()->user();
        if ($user && $user->hasRole('posyandu') && $user->posyandu_id) {
            if ($laporanMasyarakat->posyandu_id != $user->posyandu_id) {
                abort(403, 'Unauthorized action.');
            }
        }

        $laporanMasyarakat->delete();
        return redirect()->route('laporan-masyarakats.index')->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        $filters = $request->all();
        $user = auth()->user();
        if ($user && $user->hasRole('posyandu') && $user->posyandu_id) {
            $filters['posyandu_id'] = $user->posyandu_id;
        }
        return Excel::download(new LaporanMasyarakatExport($filters), 'laporan-masyarakats.xlsx');
    }
}
