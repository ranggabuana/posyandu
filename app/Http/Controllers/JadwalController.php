<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JadwalExport;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::query();
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('hari_tanggal', 'like', '%' . $request->search . '%')
                  ->orWhere('kegiatan', 'like', '%' . $request->search . '%')
                  ->orWhere('keterangan', 'like', '%' . $request->search . '%');
            });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $jadwals = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();
        
        return view('jadwals.index', compact('jadwals'));
    }

    public function create()
    {
        return view('jadwals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari_tanggal' => 'required|string|max:255',
            'jam_mulai' => 'required|string|max:10',
            'jam_selesai' => 'nullable|string|max:10',
            'kegiatan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        Jadwal::create($validated);
        return redirect()->route('jadwals.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit(Jadwal $jadwal)
    {
        return view('jadwals.edit', compact('jadwal'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $request->validate([
            'hari_tanggal' => 'required|string|max:255',
            'jam_mulai' => 'required|string|max:10',
            'jam_selesai' => 'nullable|string|max:10',
            'kegiatan' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        $jadwal->update($validated);
        return redirect()->route('jadwals.index')->with('success', 'Jadwal berhasil diubah');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwals.index')->with('success', 'Jadwal berhasil dihapus');
    }

    public function export(Request $request)
    {
        return Excel::download(new JadwalExport($request->all()), 'jadwal-pelayanan.xlsx');
    }
}
