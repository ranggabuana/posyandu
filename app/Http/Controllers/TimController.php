<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TimExport;

class TimController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->hasRole('posyandu')) {
                abort(403, 'Anda tidak memiliki akses ke halaman ini.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = Tim::query();
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('jabatan', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 10);

        $tims = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();
        
        return view('tims.index', compact('tims'));
    }

    public function create()
    {
        return view('tims.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('tims', 'public');
            $validated['foto'] = 'storage/' . $path;
        }

        Tim::create($validated);
        return redirect()->route('tims.index')->with('success', 'Anggota tim berhasil ditambahkan');
    }

    public function edit(Tim $tim)
    {
        return view('tims.edit', compact('tim'));
    }

    public function update(Request $request, Tim $tim)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($tim->foto && str_starts_with($tim->foto, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $tim->foto));
            }
            
            $path = $request->file('foto')->store('tims', 'public');
            $validated['foto'] = 'storage/' . $path;
        }

        $tim->update($validated);
        return redirect()->route('tims.index')->with('success', 'Anggota tim berhasil diubah');
    }

    public function destroy(Tim $tim)
    {
        if ($tim->foto && str_starts_with($tim->foto, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $tim->foto));
        }
        $tim->delete();
        return redirect()->route('tims.index')->with('success', 'Anggota tim berhasil dihapus');
    }

    public function export(Request $request)
    {
        return Excel::download(new TimExport($request->all()), 'tim-posyandu.xlsx');
    }
}
