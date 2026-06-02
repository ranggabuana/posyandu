<?php

namespace App\Http\Controllers;

use App\Models\LaporanMasyarakat;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanMasyarakatExport;

use App\Traits\HasHierarchicalFilter;

class LaporanMasyarakatController extends Controller
{
    use HasHierarchicalFilter;

    public function index(Request $request)
    {
        $query = LaporanMasyarakat::query()
            ->leftJoin('penduduks', 'laporan_masyarakats.nik_pelapor', '=', 'penduduks.nik')
            ->select('laporan_masyarakats.*');
            
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

        $filters = $this->getHierarchicalFilterOptions($request);
        return view('laporan-masyarakats.index', array_merge(compact('laporanMasyarakats'), $filters));
    }

    public function create()
    {
        return view('laporan-masyarakats.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'nik_pelapor' => 'required|string|max:20',
            'no_telepon' => 'nullable|string|max:20',
            'isi_laporan' => 'required|string',
            'kategori' => 'nullable|string|max:100',
            'foto_bukti' => 'nullable|image|max:2048',
            'status' => 'required|in:baru,diproses,selesai,ditolak',
        ]);

        if ($request->hasFile('foto_bukti')) {
            $validated['foto_bukti'] = $request->file('foto_bukti')->store('laporan', 'public');
        }

        LaporanMasyarakat::create($validated);
        return redirect()->route('laporan-masyarakats.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(LaporanMasyarakat $laporanMasyarakat)
    {
        return view('laporan-masyarakats.edit', compact('laporanMasyarakat'));
    }

    public function update(Request $request, LaporanMasyarakat $laporanMasyarakat)
    {
        $validated = $request->validate([
            'status' => 'required|in:baru,diproses,selesai,ditolak',
            'balasan' => 'nullable|string',
        ]);

        $laporanMasyarakat->update($validated);
        return redirect()->route('laporan-masyarakats.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy(LaporanMasyarakat $laporanMasyarakat)
    {
        $laporanMasyarakat->delete();
        return redirect()->route('laporan-masyarakats.index')->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        return Excel::download(new LaporanMasyarakatExport($request->all()), 'laporan-masyarakats.xlsx');
    }
}
