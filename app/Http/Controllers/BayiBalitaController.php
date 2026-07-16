<?php

namespace App\Http\Controllers;

use App\Models\BayiBalita;
use App\Models\Penduduk;
use App\Models\Posyandu;
use App\Models\IbuHamil;
use App\Models\PemeriksaanBalita;
use App\Models\ImunisasiBalita;
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
            ->leftJoin('posyandus', 'bayi_balitas.posyandu_id', '=', 'posyandus.id')
            ->whereRaw("TIMESTAMPDIFF(MONTH, bayi_balitas.tanggal_lahir, CURDATE()) <= 12");
        
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
        $pQuery = Penduduk::whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) <= 5");
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
            'status_akta' => 'nullable|in:punya,tidak punya',
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
            'panjang_lahir', 'goldar', 'bpjs', 'posyandu_id', 'keterangan', 'status_akta'
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
            'status_akta' => 'nullable|in:punya,tidak punya',
        ]);

        $bayiBalita->update($validated);
        
        $redirectRoute = $bayiBalita->umur_bulan <= 12 ? 'bayi-balitas.index' : 'balitas.index';
        return redirect()->route($redirectRoute)->with('success', 'Data berhasil diubah');
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
        $pemeriksaanHistory = $bayiBalita->pemeriksaans()->orderBy('tanggal_pemeriksaan', 'desc')->get();
        $imunisasiHistory = $bayiBalita->imunisasis()->orderBy('tanggal_pemberian', 'desc')->get();

        // Daftar Imunisasi Rekomendasi
        $rekomendasiImunisasi = [
            'HBO < 7 Hari',
            'HBO > 7 Hari',
            'BCG & Polio 1',
            'Pentavalen 1 & Polio 2',
            'Pentavalen 2 & Polio 3',
            'Pentavalen 3 & Polio 4',
            'PCV 1',
            'PCV 2',
            'Rotavirus 1',
            'Rotavirus 2',
            'Rotavirus 3',
            'Campak/MR 1',
            'DPT-HB-Hib Booster',
            'Campak/MR Booster',
        ];

        return view('bayi-balitas.pemeriksaan', compact('bayiBalita', 'pemeriksaanHistory', 'imunisasiHistory', 'rekomendasiImunisasi'));
    }

    public function updatePemeriksaan(Request $request, BayiBalita $bayiBalita)
    {
        $validated = $request->validate([
            // Pemeriksaan
            'tanggal_pemeriksaan' => 'nullable|date',
            'umur_bulan' => 'nullable|integer|min:0|max:60',
            'berat_badan' => 'nullable|numeric|min:0|max:100',
            'tinggi_badan' => 'nullable|numeric|min:0|max:200',
            'lingkar_lengan_atas' => 'nullable|numeric|min:0|max:100',
            'lingkar_kepala' => 'nullable|numeric|min:0|max:100',
            'asi_eksklusif' => 'nullable|in:Ya,Tidak',
            'vitamin_a' => 'nullable|in:biru,merah,tidak',
            'obat_cacing' => 'nullable|boolean',
            'pmt' => 'nullable|boolean',
            'catatan_perkembangan' => 'nullable|string',
            
            // Imunisasi
            'imunisasi_nama_vaksin' => 'nullable|string|max:255',
            'imunisasi_tanggal_pemberian' => 'nullable|date|required_with:imunisasi_nama_vaksin',
            'imunisasi_keterangan' => 'nullable|string',
            
            // Administrasi
            'status_akta' => 'nullable|in:punya,tidak punya',
        ]);

        if ($request->has('status_akta')) {
            $bayiBalita->update(['status_akta' => $request->status_akta]);
        }

        // Simpan Pemeriksaan
        if ($request->filled('tanggal_pemeriksaan') && $request->filled('umur_bulan')) {
            $statusGizi = $this->hitungStatusGizi($bayiBalita, $request->berat_badan, $request->tinggi_badan, $request->umur_bulan);

            PemeriksaanBalita::updateOrCreate(
                [
                    'bayi_balita_id' => $bayiBalita->id,
                    'umur_bulan' => $request->umur_bulan,
                ],
                [
                    'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
                    'berat_badan' => $request->berat_badan,
                    'tinggi_badan' => $request->tinggi_badan,
                    'lingkar_lengan_atas' => $request->lingkar_lengan_atas,
                    'lingkar_kepala' => $request->lingkar_kepala,
                    'asi_eksklusif' => $request->asi_eksklusif,
                    'vitamin_a' => $request->vitamin_a,
                    'obat_cacing' => $request->has('obat_cacing') ? true : false,
                    'pmt' => $request->has('pmt') ? true : false,
                    'status_gizi_bb_u' => $statusGizi['bb_u'],
                    'status_gizi_tb_u' => $statusGizi['tb_u'],
                    'status_gizi_bb_tb' => $statusGizi['bb_tb'],
                    'catatan_perkembangan' => $request->catatan_perkembangan,
                ]
            );
        }

        // Simpan Imunisasi
        if ($request->filled('imunisasi_nama_vaksin')) {
            ImunisasiBalita::create([
                'bayi_balita_id' => $bayiBalita->id,
                'nama_vaksin' => $request->imunisasi_nama_vaksin,
                'tanggal_pemberian' => $request->imunisasi_tanggal_pemberian,
                'keterangan' => $request->imunisasi_keterangan,
            ]);
        }

        $redirectRoute = $bayiBalita->umur_bulan <= 12 ? 'bayi-balitas.index' : 'balitas.index';
        return redirect()->route($redirectRoute)->with('success', 'Pemeriksaan berhasil disimpan');
    }

    public function destroyPemeriksaan(PemeriksaanBalita $pemeriksaan)
    {
        $pemeriksaan->delete();
        return back()->with('success', 'Riwayat pemeriksaan berhasil dihapus');
    }

    public function destroyImunisasi(ImunisasiBalita $imunisasi)
    {
        $imunisasi->delete();
        return back()->with('success', 'Riwayat imunisasi berhasil dihapus');
    }

    private function hitungStatusGizi($bayi, $bb, $tb, $umurBulan)
    {
        $status = ['bb_u' => 'Normal', 'tb_u' => 'Normal', 'bb_tb' => 'Normal'];
        $jk = $bayi->penduduk->kelamin ?? 'laki-laki';

        if ($bb && $umurBulan !== null) {
            // BB/U approximation
            if ($jk === 'laki-laki') {
                $medianBB = $umurBulan <= 12 
                    ? 3.3 + ($umurBulan * 0.58) 
                    : 10.3 + (($umurBulan - 12) * 0.18);
            } else {
                $medianBB = $umurBulan <= 12 
                    ? 3.2 + ($umurBulan * 0.55) 
                    : 9.7 + (($umurBulan - 12) * 0.18);
            }
            $sdBB = $medianBB * 0.12;
            $zBB = ($bb - $medianBB) / $sdBB;

            if ($zBB < -3) {
                $status['bb_u'] = 'Sangat Kurang';
            } elseif ($zBB < -2) {
                $status['bb_u'] = 'Kurang';
            } elseif ($zBB > 2) {
                $status['bb_u'] = 'Risiko Lebih';
            } else {
                $status['bb_u'] = 'Normal';
            }
        }

        if ($tb && $umurBulan !== null) {
            // TB/U approximation (Stunting)
            if ($jk === 'laki-laki') {
                if ($umurBulan <= 12) {
                    $medianTB = 50.5 + ($umurBulan * 2.1);
                } else {
                    $medianTB = 75.7 + (($umurBulan - 12) * 0.65);
                }
            } else {
                if ($umurBulan <= 12) {
                    $medianTB = 49.8 + ($umurBulan * 2.1);
                } else {
                    $medianTB = 74.0 + (($umurBulan - 12) * 0.66);
                }
            }
            $sdTB = $medianTB * 0.045;
            $zTB = ($tb - $medianTB) / $sdTB;

            if ($zTB < -3) {
                $status['tb_u'] = 'Sangat Pendek';
            } elseif ($zTB < -2) {
                $status['tb_u'] = 'Pendek';
            } else {
                $status['tb_u'] = 'Normal';
            }
        }

        if ($bb && $tb) {
            // BB/TB approximation
            $medianBBforTB = 3.1 + (($tb - 48) * 0.22);
            if ($medianBBforTB > 0) {
                $sdBBforTB = $medianBBforTB * 0.11;
                $zBBTB = ($bb - $medianBBforTB) / $sdBBforTB;

                if ($zBBTB < -3) {
                    $status['bb_tb'] = 'Gizi Buruk';
                } elseif ($zBBTB < -2) {
                    $status['bb_tb'] = 'Gizi Kurang';
                } elseif ($zBBTB > 2) {
                    $status['bb_tb'] = 'Gizi Lebih';
                } else {
                    $status['bb_tb'] = 'Normal';
                }
            }
        }

        return $status;
    }
}
