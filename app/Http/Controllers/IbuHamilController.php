<?php

namespace App\Http\Controllers;

use App\Models\IbuHamil;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\IbuHamilExport;
use App\Traits\HasHierarchicalFilter;

class IbuHamilController extends Controller
{
    use HasHierarchicalFilter;

    public function index(Request $request)
    {
        $query = IbuHamil::with('penduduk')
            ->select('ibu_hamils.*')
            ->join('penduduks', 'ibu_hamils.penduduk_id', '=', 'penduduks.id');
        
        if ($request->search) {
            $query->whereHas('penduduk', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        $query = $this->applyHierarchicalFilters($query, $request, 'penduduks');

        $sortField = $request->get('sort', 'ibu_hamils.created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 10);

        if ($sortField === 'penduduk.nama') {
            $query->orderBy('penduduks.nama', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $ibuHamils = $query->paginate($perPage)->withQueryString();
        
        $filters = $this->getHierarchicalFilterOptions($request);
        return view('ibu-hamils.index', array_merge(compact('ibuHamils'), $filters));
    }

    public function create()
    {
        $user = auth()->user();
        $query = Penduduk::where('kelamin', 'perempuan');
        
        if ($user->hasRole('posyandu') && $user->posyandu) {
            $rwDiampu = $user->posyandu->rw_diampu ?? [];
            if (!empty($rwDiampu)) {
                $query->whereIn('rw', $rwDiampu);
            }
        }

        $penduduks = $query->orderBy('nama')->get(['id', 'nama', 'nik', 'no_kk']);
        $suamis = Penduduk::where('kelamin', 'laki-laki')->orderBy('nama')->get(['id', 'nama', 'nik', 'no_kk']);
        $posyandus = \App\Models\Posyandu::orderBy('nama')->get();

        return view('ibu-hamils.create', compact('penduduks', 'suamis', 'posyandus'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id',
            'suami_id' => 'nullable|exists:penduduks,id',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'usia_kehamilan_minggu' => 'nullable|integer',
            'hpht' => 'nullable|date',
            'taksiran_persalinan' => 'nullable|date',
            'jumlah_kunjungan' => 'nullable|integer',
            'status' => 'nullable|in:aktif,selesai,gugur',
            'faktor_resiko' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        if ($user->hasRole('posyandu') && $user->posyandu_id) {
            $validated['posyandu_id'] = $user->posyandu_id;
        }

        IbuHamil::create($validated);
        return redirect()->route('ibu-hamils.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(IbuHamil $ibuHamil)
    {
        $user = auth()->user();
        $query = Penduduk::where('kelamin', 'perempuan');
        
        if ($user->hasRole('posyandu') && $user->posyandu) {
            $rwDiampu = $user->posyandu->rw_diampu ?? [];
            if (!empty($rwDiampu)) {
                $query->whereIn('rw', $rwDiampu);
            }
        }

        $penduduks = $query->orderBy('nama')->get(['id', 'nama', 'nik', 'no_kk']);
        $suamis = Penduduk::where('kelamin', 'laki-laki')->orderBy('nama')->get(['id', 'nama', 'nik', 'no_kk']);
        $posyandus = \App\Models\Posyandu::orderBy('nama')->get();

        return view('ibu-hamils.edit', compact('ibuHamil', 'penduduks', 'suamis', 'posyandus'));
    }

    public function update(Request $request, IbuHamil $ibuHamil)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id',
            'suami_id' => 'nullable|exists:penduduks,id',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'usia_kehamilan_minggu' => 'nullable|integer',
            'hpht' => 'nullable|date',
            'taksiran_persalinan' => 'nullable|date',
            'jumlah_kunjungan' => 'nullable|integer',
            'status' => 'nullable|in:aktif,selesai,gugur',
            'faktor_resiko' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        if ($user->hasRole('posyandu') && $user->posyandu_id) {
            $validated['posyandu_id'] = $user->posyandu_id;
        }

        $ibuHamil->update($validated);
        return redirect()->route('ibu-hamils.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy(IbuHamil $ibuHamil)
    {
        $ibuHamil->delete();
        return redirect()->route('ibu-hamils.index')->with('success', 'Data berhasil dihapus');
    }

    public function pemeriksaan(IbuHamil $ibuHamil)
    {
        return view('ibu-hamils.pemeriksaan', compact('ibuHamil'));
    }

    public function getSuamiByIbu(Request $request)
    {
        $ibuId = $request->get('ibu_id');
        $ibu = Penduduk::find($ibuId);
        
        $query = Penduduk::where('kelamin', 'laki-laki');
        if ($ibu) {
            $query->where('no_kk', $ibu->no_kk);
        }
        
        $suamis = $query->orderBy('nama')->get(['id', 'nama', 'nik']);
        return response()->json($suamis);
    }

    public function updatePemeriksaan(Request $request, IbuHamil $ibuHamil)
    {
        $rules = [
            'imunisasi_tt3' => 'nullable|date',
            'imunisasi_tt4' => 'nullable|date',
            'imunisasi_tt5' => 'nullable|date',
            'tablet_tambah_darah_1' => 'nullable|in:Belum,Sudah',
            'tablet_tambah_darah_2' => 'nullable|in:Belum,Sudah',
            'tablet_tambah_darah_3' => 'nullable|in:Belum,Sudah',
            'tgl_melahirkan' => 'nullable|date',
            'ditolong_oleh' => 'nullable|string',
            'bb_bayi' => 'nullable|numeric|min:0|max:100',
            'jk_bayi' => 'nullable|in:laki-laki,perempuan',
        ];

        for ($i = 1; $i <= 12; $i++) {
            $rules["bb_bulan_{$i}"] = 'nullable|numeric|min:0|max:500';
        }

        $validated = $request->validate($rules);

        $ibuHamil->update($validated);

        return redirect()->route('ibu-hamils.index')->with('success', 'Pemeriksaan Ibu Hamil berhasil disimpan');
    }

    public function export(Request $request)
    {
        return Excel::download(new IbuHamilExport($request->all()), 'ibu-hamils.xlsx');
    }
}
