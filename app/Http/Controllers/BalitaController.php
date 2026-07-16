<?php

namespace App\Http\Controllers;

use App\Models\BayiBalita;
use App\Models\Penduduk;
use App\Models\Posyandu;
use App\Exports\BalitaExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BalitaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = BayiBalita::query()
            ->join('penduduks', 'bayi_balitas.penduduk_id', '=', 'penduduks.id')
            ->select('bayi_balitas.*')
            ->whereRaw("TIMESTAMPDIFF(MONTH, bayi_balitas.tanggal_lahir, CURDATE()) > 12")
            ->whereRaw("TIMESTAMPDIFF(MONTH, bayi_balitas.tanggal_lahir, CURDATE()) <= 60");

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

        $dusuns = \App\Models\Penduduk::whereNotNull('dusun')->distinct()->pluck('dusun')->toArray();
        $rws = \App\Models\Penduduk::whereNotNull('rw')->distinct()->pluck('rw')->toArray();
        $rts = \App\Models\Penduduk::whereNotNull('rt')->distinct()->pluck('rt')->toArray();

        return view('balitas.index', compact('balitas', 'dusuns', 'rws', 'rts'));
    }

    public function create()
    {
        return redirect()->route('bayi-balitas.create');
    }

    public function store(Request $request)
    {
        return (new BayiBalitaController)->store($request);
    }

    public function edit($id)
    {
        $bayi = BayiBalita::findOrFail($id);
        return redirect()->route('bayi-balitas.edit', $bayi);
    }

    public function update(Request $request, $id)
    {
        $bayi = BayiBalita::findOrFail($id);
        return (new BayiBalitaController)->update($request, $bayi);
    }

    public function destroy($id)
    {
        $bayi = BayiBalita::findOrFail($id);
        $bayi->delete();
        return redirect()->route('balitas.index')->with('success', 'Data Balita berhasil dihapus');
    }

    public function export(Request $request)
    {
        return Excel::download(new BalitaExport($request->all()), 'balitas.xlsx');
    }

    public function pemeriksaan($id)
    {
        return redirect()->route('bayi-balitas.pemeriksaan', $id);
    }

    public function updatePemeriksaan(Request $request, $id)
    {
        $bayi = BayiBalita::findOrFail($id);
        return (new BayiBalitaController)->updatePemeriksaan($request, $bayi);
    }
}
