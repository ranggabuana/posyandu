<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\BayiBalita;
use App\Exports\BalitaExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BalitaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Balita::query()
            ->join('bayi_balitas', 'balitas.bayi_balita_id', '=', 'bayi_balitas.id')
            ->join('penduduks', 'bayi_balitas.penduduk_id', '=', 'penduduks.id')
            ->select('balitas.*');

        if ($user->hasRole('posyandu') && $user->posyandu) {
            $rwDiampu = $user->posyandu->rw_diampu ?? [];
            if (!empty($rwDiampu)) {
                $query->whereIn('penduduks.rw', $rwDiampu);
            }
        }

        if (!empty($request->search)) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('penduduks.nama', 'like', '%' . $s . '%')
                  ->orWhere('penduduks.nik', 'like', '%' . $s . '%')
                  ->orWhere('bayi_balitas.nama_ibu', 'like', '%' . $s . '%');
            });
        }

        if (!empty($request->dusun)) {
            $query->where('penduduks.dusun', $request->dusun);
        }
        if (!empty($request->rw)) {
            $query->where('penduduks.rw', $request->rw);
        }
        if (!empty($request->rt)) {
            $query->where('penduduks.rt', $request->rt);
        }

        $balitas = $query->orderBy('penduduks.nama', 'asc')->paginate(10)->withQueryString();

        // Dusuns, RWs, RTs for filter
        $dusuns = \App\Models\Penduduk::whereNotNull('dusun')->distinct()->pluck('dusun')->toArray();
        $rws = \App\Models\Penduduk::whereNotNull('rw')->distinct()->pluck('rw')->toArray();
        $rts = \App\Models\Penduduk::whereNotNull('rt')->distinct()->pluck('rt')->toArray();

        return view('balitas.index', compact('balitas', 'dusuns', 'rws', 'rts'));
    }

    public function create()
    {
        $user = auth()->user();
        $query = BayiBalita::query()
            ->whereDoesntHave('balita')
            ->join('penduduks', 'bayi_balitas.penduduk_id', '=', 'penduduks.id')
            ->select('bayi_balitas.*');

        if ($user->hasRole('posyandu') && $user->posyandu) {
            $rwDiampu = $user->posyandu->rw_diampu ?? [];
            if (!empty($rwDiampu)) {
                $query->whereIn('penduduks.rw', $rwDiampu);
            }
        }

        $bayis = $query->orderBy('penduduks.nama', 'asc')->get();

        return view('balitas.create', compact('bayis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bayi_balita_id' => 'required|exists:bayi_balitas,id|unique:balitas,bayi_balita_id',
            'status_akta' => 'required|in:punya,tidak punya',
        ]);

        Balita::create($validated);

        return redirect()->route('balitas.index')->with('success', 'Data Balita berhasil ditambahkan');
    }

    public function edit(Balita $balita)
    {
        $user = auth()->user();
        $query = BayiBalita::query()
            ->where(function($q) use ($balita) {
                $q->whereDoesntHave('balita')
                  ->orWhere('id', $balita->bayi_balita_id);
            })
            ->join('penduduks', 'bayi_balitas.penduduk_id', '=', 'penduduks.id')
            ->select('bayi_balitas.*');

        if ($user->hasRole('posyandu') && $user->posyandu) {
            $rwDiampu = $user->posyandu->rw_diampu ?? [];
            if (!empty($rwDiampu)) {
                $query->whereIn('penduduks.rw', $rwDiampu);
            }
        }

        $bayis = $query->orderBy('penduduks.nama', 'asc')->get();

        return view('balitas.edit', compact('balita', 'bayis'));
    }

    public function update(Request $request, Balita $balita)
    {
        $validated = $request->validate([
            'bayi_balita_id' => 'required|exists:bayi_balitas,id|unique:balitas,bayi_balita_id,' . $balita->id,
            'status_akta' => 'required|in:punya,tidak punya',
        ]);

        $balita->update($validated);

        return redirect()->route('balitas.index')->with('success', 'Data Balita berhasil diubah');
    }

    public function destroy(Balita $balita)
    {
        $balita->delete();
        return redirect()->route('balitas.index')->with('success', 'Data Balita berhasil dihapus');
    }

    public function export(Request $request)
    {
        return Excel::download(new BalitaExport($request->all()), 'balitas.xlsx');
    }

    public function pemeriksaan(Balita $balita)
    {
        return view('balitas.pemeriksaan', compact('balita'));
    }

    public function updatePemeriksaan(Request $request, Balita $balita)
    {
        $rules = [
            'status_akta' => 'required|in:punya,tidak punya',
            'vitamin_a_18' => 'nullable|in:sudah,belum',
            'vitamin_a_24' => 'nullable|in:sudah,belum',
            'vitamin_a_30' => 'nullable|in:sudah,belum',
            'vitamin_a_36' => 'nullable|in:sudah,belum',
            'vitamin_a_42' => 'nullable|in:sudah,belum',
            'vitamin_a_48' => 'nullable|in:sudah,belum',
            'vitamin_a_54' => 'nullable|in:sudah,belum',
            'vitamin_a_60' => 'nullable|in:sudah,belum',
            'booster_dpt_hb_hib' => 'nullable|in:sudah,belum',
            'booster_campak' => 'nullable|in:sudah,belum',
            'keterangan_balita' => 'nullable|string',
        ];

        for ($i = 13; $i <= 60; $i++) {
            $rules["bb_bulan_{$i}"] = 'nullable|numeric|min:0|max:100';
        }

        $validated = $request->validate($rules);

        $balita->update($validated);

        return redirect()->route('balitas.index')->with('success', 'Pemeriksaan Balita berhasil disimpan');
    }
}
