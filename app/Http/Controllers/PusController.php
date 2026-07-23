<?php

namespace App\Http\Controllers;

use App\Models\Pus;
use App\Models\Penduduk;
use App\Models\Posyandu;
use App\Models\Pengaturan;
use App\Traits\HasHierarchicalFilter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PusExport;

class PusController extends Controller
{
    use HasHierarchicalFilter;

    public function index(Request $request)
    {
        $query = Pus::with(['suami', 'istri', 'posyandu'])
            ->select('puses.*')
            ->join('penduduks', 'puses.istri_id', '=', 'penduduks.id');

        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('suami', function($sq) use ($search) {
                    $sq->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('nik', 'like', '%' . $search . '%');
                })->orWhereHas('istri', function($iq) use ($search) {
                    $iq->where('nama', 'like', '%' . $search . '%')
                      ->orWhere('nik', 'like', '%' . $search . '%');
                });
            });
        }

        $query = $this->applyHierarchicalFilters($query, $request, 'penduduks');

        $perPage = $request->get('per_page', 10);
        $puses = $query->orderBy('puses.created_at', 'desc')->paginate($perPage)->withQueryString();

        $filters = $this->getHierarchicalFilterOptions($request);
        return view('puses.index', array_merge(compact('puses'), $filters));
    }

    public function create()
    {
        $minAge = Pengaturan::where('key', 'pus_umur_min')->value('value') ?? 15;
        $maxAge = Pengaturan::where('key', 'pus_umur_max')->value('value') ?? 49;

        $pria = Penduduk::where('kelamin', 'laki-laki')
            ->whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge])
            ->orderBy('nama')->get();

        $wanita = Penduduk::where('kelamin', 'perempuan')
            ->whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge])
            ->orderBy('nama')->get();

        $posyandus = Posyandu::orderBy('nama')->get();

        return view('puses.create', compact('pria', 'wanita', 'posyandus'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'suami_id' => 'nullable|exists:penduduks,id',
            'istri_id' => 'required|exists:penduduks,id',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'keterangan' => 'nullable|string'
        ]);

        if ($user->hasRole('posyandu') && $user->posyandu_id) {
            $validated['posyandu_id'] = $user->posyandu_id;
        }

        Pus::create($validated);

        return redirect()->route('puses.index')->with('success', 'Data PUS berhasil ditambahkan.');
    }

    public function edit(Pus $pus)
    {
        $minAge = Pengaturan::where('key', 'pus_umur_min')->value('value') ?? 15;
        $maxAge = Pengaturan::where('key', 'pus_umur_max')->value('value') ?? 49;

        $pria = Penduduk::where('kelamin', 'laki-laki')
            ->whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge])
            ->orderBy('nama')->get();

        $wanita = Penduduk::where('kelamin', 'perempuan')
            ->whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge])
            ->orderBy('nama')->get();

        $posyandus = Posyandu::orderBy('nama')->get();

        return view('puses.edit', compact('pus', 'pria', 'wanita', 'posyandus'));
    }

    public function update(Request $request, Pus $pus)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'suami_id' => 'nullable|exists:penduduks,id',
            'istri_id' => 'required|exists:penduduks,id',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'keterangan' => 'nullable|string'
        ]);

        if ($user->hasRole('posyandu') && $user->posyandu_id) {
            $validated['posyandu_id'] = $user->posyandu_id;
        }

        $pus->update($validated);

        return redirect()->route('puses.index')->with('success', 'Data PUS berhasil diperbarui.');
    }

    public function destroy(Pus $pus)
    {
        $pus->delete();
        return redirect()->route('puses.index')->with('success', 'Data PUS berhasil dihapus.');
    }

    public function export(Request $request)
    {
        return Excel::download(new PusExport($request->all()), 'puses.xlsx');
    }

    public function getIstriBySuami(Request $request)
    {
        $suamiId = $request->get('suami_id');
        $suami = Penduduk::find($suamiId);
        
        $minAge = Pengaturan::where('key', 'pus_umur_min')->value('value') ?? 15;
        $maxAge = Pengaturan::where('key', 'pus_umur_max')->value('value') ?? 49;

        $query = Penduduk::where('kelamin', 'perempuan')
            ->whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge]);

        if ($suami) {
            $query->where('no_kk', $suami->no_kk);
        }

        $wanita = $query->orderBy('nama')->get(['id', 'nama', 'nik']);
        
        return response()->json($wanita);
    }
}
