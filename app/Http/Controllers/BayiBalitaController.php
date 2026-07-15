<?php

namespace App\Http\Controllers;

use App\Models\BayiBalita;
use App\Models\Penduduk;
use App\Models\Posyandu;
use App\Models\IbuHamil;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BayiBalitaExport;
use App\Traits\HasHierarchicalFilter;

class BayiBalitaController extends Controller
{
    use HasHierarchicalFilter;

    public function index(Request $request)
    {
        $query = BayiBalita::with(['penduduk', 'posyandu'])
            ->select('bayi_balitas.*')
            ->join('penduduks', 'bayi_balitas.penduduk_id', '=', 'penduduks.id')
            ->leftJoin('posyandus', 'bayi_balitas.posyandu_id', '=', 'posyandus.id');
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->whereHas('penduduk', function ($pq) use ($request) {
                    $pq->where('nama', 'like', '%' . $request->search . '%');
                })->orWhere('nama_ibu', 'like', '%' . $request->search . '%');
            });
        }

        $query = $this->applyHierarchicalFilters($query, $request, 'penduduks');

        $sortField = $request->get('sort', 'bayi_balitas.created_at');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = $request->get('per_page', 10);

        if ($sortField === 'penduduk.nama') {
            $query->orderBy('penduduks.nama', $sortDirection);
        } elseif ($sortField === 'posyandu.nama') {
            $query->orderBy('posyandus.nama', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        $bayiBalitas = $query->paginate($perPage)->withQueryString();
        
        $filters = $this->getHierarchicalFilterOptions($request);
        return view('bayi-balitas.index', array_merge(compact('bayiBalitas'), $filters));
    }

    public function create()
    {
        $user = auth()->user();
        $pQuery = Penduduk::query();
        $iQuery = Penduduk::whereIn('id', IbuHamil::pluck('penduduk_id'));
        
        if ($user->hasRole('posyandu') && $user->posyandu) {
            $rwDiampu = $user->posyandu->rw_diampu ?? [];
            if (!empty($rwDiampu)) {
                $pQuery->whereIn('rw', $rwDiampu);
                $iQuery->whereIn('rw', $rwDiampu);
            }
        }

        $penduduks = $pQuery->orderBy('nama')->get(['id', 'nama', 'nik', 'tanggallahir']);
        $ibus = $iQuery->orderBy('nama')->get(['id', 'nama', 'nik']);
        $posyandus = Posyandu::orderBy('nama')->get();
        return view('bayi-balitas.create', compact('penduduks', 'ibus', 'posyandus'));
    }

    public function store(Request $request)
    {
        $rules = [
            'nama_ibu' => 'nullable|string|max:255',
            'berat_lahir' => 'nullable|numeric',
            'panjang_lahir' => 'nullable|numeric',
            'goldar' => 'nullable|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-',
            'bpjs' => 'nullable|boolean',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'keterangan' => 'nullable|string',
        ];

        if ($request->input('input_manual') == '1') {
            $rules['nama'] = 'required|string|max:255';
            $rules['tempat_lahir'] = 'required|string|max:255';
            $rules['tanggal_lahir_manual'] = 'required|date';
            $rules['kelamin'] = 'required|in:laki-laki,perempuan';
            $rules['nik'] = 'nullable|string|size:16|unique:penduduks,nik';
        } else {
            $rules['penduduk_id'] = 'required|exists:penduduks,id';
            $rules['tanggal_lahir'] = 'required|date';
        }

        $validated = $request->validate($rules);

        if ($request->input('input_manual') == '1') {
            $validated['tanggal_lahir'] = $validated['tanggal_lahir_manual'];
            unset($validated['tanggal_lahir_manual']);

            $mother = null;
            if (!empty($validated['nama_ibu'])) {
                $mother = Penduduk::where('nama', $validated['nama_ibu'])->first();
            }

            $nik = $request->input('nik');
            if (empty($nik)) {
                do {
                    $nik = '99' . date('ymd') . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
                } while (Penduduk::where('nik', $nik)->exists());
            }

            $penduduk = Penduduk::create([
                'nama' => $validated['nama'],
                'nik' => $nik,
                'no_kk' => $mother->no_kk ?? '9900000000000000',
                'nama_kk' => $mother->nama_kk ?? $mother->nama ?? $validated['nama'] . ' KK',
                'hubungan_keluarga' => 'anak',
                'kelamin' => $validated['kelamin'],
                'tempatlahir' => $validated['tempat_lahir'],
                'tanggallahir' => $validated['tanggal_lahir'],
                'status_kawin' => 'belum kawin',
                'alamat' => $mother->alamat ?? null,
                'rw' => $mother->rw ?? null,
                'rt' => $mother->rt ?? null,
                'dusun' => $mother->dusun ?? null,
                'bpjs' => isset($validated['bpjs']) ? (bool)$validated['bpjs'] : false,
            ]);

            $validated['penduduk_id'] = $penduduk->id;
        }

        $bayiData = collect($validated)->only([
            'penduduk_id', 'nama_ibu', 'tanggal_lahir', 'berat_lahir', 
            'panjang_lahir', 'goldar', 'bpjs', 'posyandu_id', 'keterangan'
        ])->toArray();

        BayiBalita::create($bayiData);
        return redirect()->route('bayi-balitas.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(BayiBalita $bayiBalita)
    {
        $user = auth()->user();
        $pQuery = Penduduk::query();
        $iQuery = Penduduk::whereIn('id', IbuHamil::pluck('penduduk_id'));
        
        if ($user->hasRole('posyandu') && $user->posyandu) {
            $rwDiampu = $user->posyandu->rw_diampu ?? [];
            if (!empty($rwDiampu)) {
                $pQuery->whereIn('rw', $rwDiampu);
                $iQuery->whereIn('rw', $rwDiampu);
            }
        }

        $penduduks = $pQuery->orderBy('nama')->get(['id', 'nama', 'nik']);
        $ibus = $iQuery->orderBy('nama')->get(['id', 'nama', 'nik']);
        $posyandus = Posyandu::orderBy('nama')->get();
        return view('bayi-balitas.edit', compact('bayiBalita', 'penduduks', 'ibus', 'posyandus'));
    }

    public function update(Request $request, BayiBalita $bayiBalita)
    {
        $validated = $request->validate([
            'penduduk_id' => 'required|exists:penduduks,id',
            'nama_ibu' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'berat_lahir' => 'nullable|numeric',
            'panjang_lahir' => 'nullable|numeric',
            'goldar' => 'nullable|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-',
            'bpjs' => 'nullable|boolean',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'keterangan' => 'nullable|string',
        ]);

        $bayiBalita->update($validated);
        return redirect()->route('bayi-balitas.index')->with('success', 'Data berhasil diubah');
    }

    public function destroy(BayiBalita $bayiBalita)
    {
        $bayiBalita->delete();
        return redirect()->route('bayi-balitas.index')->with('success', 'Data berhasil dihapus');
    }

    public function export(Request $request)
    {
        return Excel::download(new BayiBalitaExport($request->all()), 'bayi-balitas.xlsx');
    }

    public function pemeriksaan(BayiBalita $bayiBalita)
    {
        return view('bayi-balitas.pemeriksaan', compact('bayiBalita'));
    }

    public function updatePemeriksaan(Request $request, BayiBalita $bayiBalita)
    {
        $rules = [
            'imunisasi_hbo_kurang_7_hari' => 'nullable|in:L,P',
            'imunisasi_hbo_lebih_7_hari' => 'nullable|in:L,P',
            'imunisasi_bcg_polio1' => 'nullable|in:L,P',
            'imunisasi_pentavalen1_polio2' => 'nullable|in:L,P',
            'imunisasi_pentavalen2_polio3' => 'nullable|in:L,P',
            'imunisasi_pentavalen3_polio4' => 'nullable|in:L,P',
            'imunisasi_keterangan' => 'nullable|string',
        ];

        for ($i = 1; $i <= 12; $i++) {
            $rules["bb_bulan_{$i}"] = 'nullable|numeric|min:0|max:100';
            $rules["tb_bulan_{$i}"] = 'nullable|numeric|min:0|max:200';
            $rules["lla_bulan_{$i}"] = 'nullable|numeric|min:0|max:100';
            $rules["lk_bulan_{$i}"] = 'nullable|numeric|min:0|max:100';
        }

        $validated = $request->validate($rules);

        $bayiBalita->update($validated);

        return redirect()->route('bayi-balitas.index')->with('success', 'Pemeriksaan Bayi/Balita berhasil disimpan');
    }
}
