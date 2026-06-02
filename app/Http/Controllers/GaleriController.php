<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\Posyandu;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GaleriExport;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        // Table name is 'galeries', view folder is 'galeries', route name is 'galeries'
        $query = Galeri::with('posyandu')
            ->select('galeries.*')
            ->leftJoin('posyandus', 'galeries.posyandu_id', '=', 'posyandus.id');
        
        if ($request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $sortField = $request->get('sort', 'galeries.created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 10);

        if ($sortField === 'posyandu.nama') {
            $query->orderBy('posyandus.nama', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $galeries = $query->paginate($perPage)->withQueryString();
        
        return view('galeries.index', compact('galeries'));
    }

    public function create()
    {
        $posyandus = Posyandu::orderBy('nama')->get();
        return view('galeries.create', compact('posyandus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'judul' => 'required|string|max:255',
            'foto' => 'required|image|max:2048', 
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('galeri', 'public');
        }

        Galeri::create($validated);
        return redirect()->route('galeries.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(Galeri $galeri)
    {
        $posyandus = Posyandu::orderBy('nama')->get();
        return view('galeries.edit', compact('galeri', 'posyandus'));
    }

    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'judul' => 'required|string|max:255',
            'foto' => 'nullable|image|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('foto')) {
            if ($galeri->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($galeri->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($galeri->foto);
            }
            $validated['foto'] = $request->file('foto')->store('galeri', 'public');
        } else {
            unset($validated['foto']);
        }

        $galeri->update($validated);
        return redirect()->route('galeries.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy(Galeri $galeri)
    {
        if ($galeri->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($galeri->foto)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($galeri->foto);
        }
        $galeri->delete();
        return redirect()->route('galeries.index')->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        return Excel::download(new GaleriExport($request->all()), 'galeries.xlsx');
    }
}
