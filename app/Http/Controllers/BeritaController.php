<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BeritaExport;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $query = Berita::with(['user.posyandu', 'user.roles']);
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $beritas = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();
        
        return view('beritas.index', compact('beritas'));
    }

    public function create()
    {
        return view('beritas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:beritas,slug',
            'konten' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
            'kategori' => 'nullable|string|max:100',
            'penulis' => 'nullable|exists:users,id',
            'status' => 'nullable|in:draft,publikasi',
        ]);

        if (empty($validated['penulis'])) {
            $validated['penulis'] = auth()->id();
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['judul']);
        }

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        Berita::create($validated);
        return redirect()->route('beritas.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(Berita $berita)
    {
        return view('beritas.edit', compact('berita'));
    }

    public function update(Request $request, Berita $berita)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:beritas,slug,' . $berita->id,
            'konten' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
            'kategori' => 'nullable|string|max:100',
            'penulis' => 'nullable|exists:users,id',
            'status' => 'nullable|in:draft,publikasi',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['judul']);
        }

        if ($request->hasFile('gambar')) {
            if ($berita->gambar && \Illuminate\Support\Facades\Storage::disk('public')->exists($berita->gambar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($berita->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('berita', 'public');
        } else {
            unset($validated['gambar']);
        }

        $berita->update($validated);
        return redirect()->route('beritas.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy(Berita $berita)
    {
        $berita->delete();
        return redirect()->route('beritas.index')->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        return Excel::download(new BeritaExport($request->all()), 'beritas.xlsx');
    }
}
