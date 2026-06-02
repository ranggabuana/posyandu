<?php

namespace App\Http\Controllers;

use App\Models\BukuTamu;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BukuTamuExport;

class BukuTamuController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuTamu::query();
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('instansi', 'like', '%' . $request->search . '%')
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
        return view('buku-tamus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'keperluan' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'tanggal_kunjungan' => 'required|date',
            'jam_masuk' => 'required',
            'jam_keluar' => 'nullable',
            'keterangan' => 'nullable|string',
        ]);
        BukuTamu::create($validated);
        return redirect()->route('buku-tamus.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(BukuTamu $bukuTamu)
    {
        return view('buku-tamus.edit', compact('bukuTamu'));
    }

    public function update(Request $request, BukuTamu $bukuTamu)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'keperluan' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'tanggal_kunjungan' => 'required|date',
            'jam_masuk' => 'required',
            'jam_keluar' => 'nullable',
            'keterangan' => 'nullable|string',
        ]);
        $bukuTamu->update($validated);
        return redirect()->route('buku-tamus.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy(BukuTamu $bukuTamu)
    {
        $bukuTamu->delete();
        return redirect()->route('buku-tamus.index')->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        return Excel::download(new BukuTamuExport($request->all()), 'buku-tamus.xlsx');
    }
}
