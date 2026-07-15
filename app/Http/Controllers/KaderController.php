<?php

namespace App\Http\Controllers;

use App\Models\Kader;
use App\Models\Posyandu;
use App\Models\Penduduk;
use Illuminate\Http\Request;

class KaderController extends Controller
{
    public function getPenduduks(Request $request)
    {
        $posyanduId = $request->get('posyandu_id');
        $query = Penduduk::query();

        if ($posyanduId) {
            $posyandu = Posyandu::find($posyanduId);
            if ($posyandu) {
                $rwDiampu = $posyandu->rw_diampu ?? [];
                if (!empty($rwDiampu)) {
                    $query->whereIn('rw', $rwDiampu);
                } else {
                    $query->whereRaw('1 = 0');
                }
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $penduduks = $query->orderBy('nama')->get(['id', 'nama', 'nik']);

        return response()->json($penduduks);
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Kader::with(['penduduk', 'posyandu']);

        // Filter by user role posyandu
        if ($user->hasRole('posyandu')) {
            $posyanduId = $user->posyandu_id;
            $query->where('posyandu_id', $posyanduId);
        } else {
            if ($request->filled('posyandu_id')) {
                $query->where('posyandu_id', $request->posyandu_id);
            }
        }

        // Search logic
        if ($request->search) {
            $query->whereHas('penduduk', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }

        $sortField = $request->get('sort', 'kaders.created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 10);

        if ($sortField === 'penduduk.nama') {
            $query->join('penduduks', 'kaders.penduduk_id', '=', 'penduduks.id')
                  ->orderBy('penduduks.nama', $sortDirection)
                  ->select('kaders.*');
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $kaders = $query->paginate($perPage)->withQueryString();
        $posyandus = Posyandu::orderBy('nama')->get();

        return view('kaders.index', compact('kaders', 'posyandus'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $query = Penduduk::query();
        $posyanduId = null;

        if ($user->hasRole('posyandu') && $user->posyandu) {
            $posyanduId = $user->posyandu_id;
        } else {
            $posyanduId = $request->get('posyandu_id') ?? old('posyandu_id');
        }

        if ($posyanduId) {
            $posyandu = Posyandu::find($posyanduId);
            if ($posyandu) {
                $rwDiampu = $posyandu->rw_diampu ?? [];
                if (!empty($rwDiampu)) {
                    $query->whereIn('rw', $rwDiampu);
                } else {
                    $query->whereRaw('1 = 0');
                }
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $penduduks = $query->orderBy('nama')->get(['id', 'nama', 'nik', 'no_kk']);
        $posyandus = Posyandu::orderBy('nama')->get();

        return view('kaders.create', compact('penduduks', 'posyandus'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'penduduk_id' => 'required|exists:penduduks,id',
            'jabatan' => 'required|string|max:255',
            'tanggal_mulai_tugas' => 'nullable|date',
            'status_aktif' => 'required|boolean',
            'pelatihan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ];

        if ($user->hasRole('posyandu')) {
            $posyanduId = $user->posyandu_id;
            if (!$posyanduId) {
                return back()->withErrors(['error' => 'User tidak terasosiasi dengan Posyandu manapun.']);
            }
        } else {
            $rules['posyandu_id'] = 'required|exists:posyandus,id';
        }

        $validated = $request->validate($rules);

        if ($user->hasRole('posyandu')) {
            $validated['posyandu_id'] = $user->posyandu_id;
        }

        Kader::create($validated);

        return redirect()->route('kaders.index')->with('success', 'Data Kader berhasil ditambahkan');
    }

    public function edit(Kader $kader)
    {
        $user = auth()->user();

        // Enforce role boundary
        if ($user->hasRole('posyandu') && $kader->posyandu_id !== $user->posyandu_id) {
            abort(403, 'Anda tidak diizinkan mengakses data ini.');
        }

        $query = Penduduk::query();
        $posyanduId = null;

        if ($user->hasRole('posyandu')) {
            $posyanduId = $user->posyandu_id;
        } else {
            $posyanduId = $kader->posyandu_id;
        }

        if ($posyanduId) {
            $posyandu = Posyandu::find($posyanduId);
            if ($posyandu) {
                $rwDiampu = $posyandu->rw_diampu ?? [];
                if (!empty($rwDiampu)) {
                    $query->where(function($q) use ($rwDiampu, $kader) {
                        $q->whereIn('rw', $rwDiampu)
                          ->orWhere('id', $kader->penduduk_id);
                    });
                } else {
                    $query->where('id', $kader->penduduk_id);
                }
            } else {
                $query->where('id', $kader->penduduk_id);
            }
        }

        $penduduks = $query->orderBy('nama')->get(['id', 'nama', 'nik', 'no_kk']);
        $posyandus = Posyandu::orderBy('nama')->get();

        return view('kaders.edit', compact('kader', 'penduduks', 'posyandus'));
    }

    public function update(Request $request, Kader $kader)
    {
        $user = auth()->user();

        // Enforce role boundary
        if ($user->hasRole('posyandu') && $kader->posyandu_id !== $user->posyandu_id) {
            abort(403, 'Anda tidak diizinkan mengubah data ini.');
        }

        $rules = [
            'penduduk_id' => 'required|exists:penduduks,id',
            'jabatan' => 'required|string|max:255',
            'tanggal_mulai_tugas' => 'nullable|date',
            'status_aktif' => 'required|boolean',
            'pelatihan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ];

        if (!$user->hasRole('posyandu')) {
            $rules['posyandu_id'] = 'required|exists:posyandus,id';
        }

        $validated = $request->validate($rules);

        if ($user->hasRole('posyandu')) {
            $validated['posyandu_id'] = $user->posyandu_id;
        }

        $kader->update($validated);

        return redirect()->route('kaders.index')->with('success', 'Data Kader berhasil diubah');
    }

    public function destroy(Kader $kader)
    {
        $user = auth()->user();

        // Enforce role boundary
        if ($user->hasRole('posyandu') && $kader->posyandu_id !== $user->posyandu_id) {
            abort(403, 'Anda tidak diizinkan menghapus data ini.');
        }

        $kader->delete();

        return redirect()->route('kaders.index')->with('success', 'Data Kader berhasil dihapus');
    }
}
