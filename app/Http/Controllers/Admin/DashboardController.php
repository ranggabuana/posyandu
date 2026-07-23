<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\IbuHamil;
use App\Models\BayiBalita;
use App\Models\PemeriksaanBalita;
use App\Models\Lansia;
use App\Models\PemeriksaanLansia;
use App\Models\Remaja;
use App\Models\PemeriksaanRemaja;
use App\Models\Pus;
use App\Models\Posyandu;
use App\Models\Kader;
use App\Models\BukuTamu;
use App\Models\LaporanMasyarakat;
use App\Models\Pengaturan;

class DashboardController extends Controller
{
    /**
     * Display the rich admin dashboard with complete lifecycle metrics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isPosyanduUser = $user && $user->hasRole('posyandu') && $user->posyandu;
        $posyanduInfo = $isPosyanduUser ? $user->posyandu : null;
        $rwDiampu = $isPosyanduUser ? ($user->posyandu->rw_diampu ?? []) : [];

        // Helper to scope query by RW for Posyandu user
        $scopeRw = function($query, $tablePrefix = '') use ($isPosyanduUser, $rwDiampu) {
            if ($isPosyanduUser) {
                $column = $tablePrefix ? $tablePrefix . '.rw' : 'rw';
                if (!empty($rwDiampu)) {
                    $query->whereIn($column, $rwDiampu);
                } else {
                    $query->whereRaw('1 = 0');
                }
            }
            return $query;
        };

        // Helper to scope query by relation to Penduduk's RW
        $scopePendudukRw = function($query, $relationName = 'penduduk') use ($isPosyanduUser, $rwDiampu) {
            if ($isPosyanduUser) {
                if (!empty($rwDiampu)) {
                    $query->whereHas($relationName, function($q) use ($rwDiampu) {
                        $q->whereIn('rw', $rwDiampu);
                    });
                } else {
                    $query->whereRaw('1 = 0');
                }
            }
            return $query;
        };

        // 1. Min/Max Settings
        $lansiaMin = Pengaturan::where('key', 'umur_lansia_min')->value('value') ?? 60;
        $remajaMin = Pengaturan::where('key', 'remaja_umur_min')->value('value') ?? 10;
        $remajaMax = Pengaturan::where('key', 'remaja_umur_max')->value('value') ?? 18;
        $wusMin = Pengaturan::where('key', 'wus_umur_min')->value('value') ?? 15;
        $wusMax = Pengaturan::where('key', 'wus_umur_max')->value('value') ?? 49;

        // 2. Main Lifecycle Counts
        $totalPenduduk = $scopeRw(Penduduk::query())->count();
        $totalIbuHamil = $scopePendudukRw(IbuHamil::query(), 'penduduk')->count();
        $totalBayi = $scopePendudukRw(BayiBalita::whereRaw("TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) <= 12"), 'penduduk')->count();
        $totalBalita = $scopePendudukRw(BayiBalita::whereRaw("TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) > 12 AND TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) <= 60"), 'penduduk')->count();
        $totalRemaja = $scopeRw(Penduduk::whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN ? AND ?", [$remajaMin, $remajaMax]))->count();
        $totalLansia = $scopeRw(Penduduk::whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) >= ?", [$lansiaMin]))->count();
        $totalWus = $scopeRw(Penduduk::where('kelamin', 'perempuan')->whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN ? AND ?", [$wusMin, $wusMax]))->count();

        $pusQuery = Pus::query();
        if ($isPosyanduUser) {
            if (!empty($rwDiampu)) {
                $pusQuery->whereHas('istri', function($q) use ($rwDiampu) {
                    $q->whereIn('rw', $rwDiampu);
                });
            } else {
                $pusQuery->whereRaw('1 = 0');
            }
        }
        $totalPus = $pusQuery->count();

        // 3. Operational Counts
        if ($isPosyanduUser) {
            $totalPosyandu = 1;
            $totalKader = Kader::where('posyandu_id', $user->posyandu_id)->count();
            $totalBukuTamu = BukuTamu::where('posyandu_id', $user->posyandu_id)->count();
            $totalLaporan = LaporanMasyarakat::where('posyandu_id', $user->posyandu_id)->count();
            $laporanPending = LaporanMasyarakat::where('posyandu_id', $user->posyandu_id)->whereIn('status', ['pending', 'menunggu', 'belum_proses'])->count();
        } else {
            $totalPosyandu = Posyandu::count();
            $totalKader = Kader::count();
            $totalBukuTamu = BukuTamu::count();
            $totalLaporan = LaporanMasyarakat::count();
            $laporanPending = LaporanMasyarakat::whereIn('status', ['pending', 'menunggu', 'belum_proses'])->count();
        }

        // 4. Examination Log Counts
        $ibuHamilExamCount = 0;
        for ($i = 1; $i <= 12; $i++) {
            $ibuHamilExamCount += $scopePendudukRw(IbuHamil::whereNotNull("bb_bulan_{$i}"), 'penduduk')->count();
        }
        $bayiExamCount = $scopePendudukRw(PemeriksaanBalita::where('umur_bulan', '<=', 12), 'bayiBalita.penduduk')->count();
        $balitaExamCount = $scopePendudukRw(PemeriksaanBalita::where('umur_bulan', '>', 12)->where('umur_bulan', '<=', 60), 'bayiBalita.penduduk')->count();
        $remajaExamCount = $scopePendudukRw(PemeriksaanRemaja::query(), 'remaja.penduduk')->count();
        $lansiaExamCount = $scopePendudukRw(PemeriksaanLansia::query(), 'lansia.penduduk')->count();

        // 5. Stunting & Nutrition Summary (Balita) with Detailed Person Lists
        $latestBalitaExamsQuery = PemeriksaanBalita::with('bayiBalita.penduduk')
            ->select('pemeriksaan_balitas.*')
            ->join(\DB::raw('(SELECT bayi_balita_id, MAX(tanggal_pemeriksaan) as max_date FROM pemeriksaan_balitas GROUP BY bayi_balita_id) latest'), function($join) {
                $join->on('pemeriksaan_balitas.bayi_balita_id', '=', 'latest.bayi_balita_id')
                     ->on('pemeriksaan_balitas.tanggal_pemeriksaan', '=', 'latest.max_date');
            });
        $latestBalitaExams = $scopePendudukRw($latestBalitaExamsQuery, 'bayiBalita.penduduk')->get();

        $listStunting = [];
        $listGiziKurang = [];
        $listGiziNormal = [];

        foreach ($latestBalitaExams as $ex) {
            $penduduk = $ex->bayiBalita->penduduk ?? null;
            if (!$penduduk) continue;

            $item = [
                'id' => $ex->bayi_balita_id,
                'nama' => $penduduk->nama,
                'nik' => $penduduk->nik,
                'umur' => $ex->umur_bulan . ' Bulan',
                'wilayah' => ($penduduk->dusun ?? '-') . ' (RW ' . ($penduduk->rw ?? '00') . '/RT ' . ($penduduk->rt ?? '00') . ')',
                'tanggal' => \Carbon\Carbon::parse($ex->tanggal_pemeriksaan)->translatedFormat('d M Y'),
                'link' => route('bayi-balitas.pemeriksaan', $ex->bayi_balita_id),
            ];

            $tbStatus = strtolower($ex->status_stunting ?? '');
            $bbStatus = strtolower($ex->status_gizi ?? '');

            if (str_contains($tbStatus, 'pendek') || ($ex->z_score_tb_u !== null && $ex->z_score_tb_u < -2.0)) {
                $itemStunting = $item;
                $itemStunting['detail'] = 'Z-Score TB/U: ' . ($ex->z_score_tb_u ?? '-') . ' SD (' . ($ex->status_stunting ?? 'Stunting/Pendek') . ')';
                $listStunting[] = $itemStunting;
            }

            if (str_contains($bbStatus, 'kurang') || ($ex->z_score_bb_u !== null && $ex->z_score_bb_u < -2.0)) {
                $itemGizi = $item;
                $itemGizi['detail'] = 'Z-Score BB/U: ' . ($ex->z_score_bb_u ?? '-') . ' SD (' . ($ex->status_gizi ?? 'Gizi Kurang') . ')';
                $listGiziKurang[] = $itemGizi;
            }

            if (!str_contains($tbStatus, 'pendek') && !str_contains($bbStatus, 'kurang')) {
                $itemNormal = $item;
                $itemNormal['detail'] = 'Status TB/U: ' . ($ex->status_stunting ?? 'Normal') . ' | BB/U: ' . ($ex->status_gizi ?? 'Baik');
                $listGiziNormal[] = $itemNormal;
            }
        }

        // 6. Adolescent Screening Summary (Remaja) with Detailed Person Lists
        $latestRemajaExamsQuery = PemeriksaanRemaja::with('remaja.penduduk')
            ->select('pemeriksaan_remajas.*')
            ->join(\DB::raw('(SELECT remaja_id, MAX(tanggal_pemeriksaan) as max_date FROM pemeriksaan_remajas GROUP BY remaja_id) latest'), function($join) {
                $join->on('pemeriksaan_remajas.remaja_id', '=', 'latest.remaja_id')
                     ->on('pemeriksaan_remajas.tanggal_pemeriksaan', '=', 'latest.max_date');
            });
        $latestRemajaExams = $scopePendudukRw($latestRemajaExamsQuery, 'remaja.penduduk')->get();

        $listRemajaKek = [];
        $listRemajaAnemia = [];
        $listRemajaTtd = [];

        foreach ($latestRemajaExams as $ex) {
            $penduduk = $ex->remaja->penduduk ?? null;
            if (!$penduduk) continue;

            $item = [
                'id' => $ex->remaja_id,
                'nama' => $penduduk->nama,
                'nik' => $penduduk->nik,
                'umur' => ($penduduk->umur ?? $penduduk->usia ?? '-') . ' Tahun',
                'wilayah' => ($penduduk->dusun ?? '-') . ' (RW ' . ($penduduk->rw ?? '00') . '/RT ' . ($penduduk->rt ?? '00') . ')',
                'tanggal' => \Carbon\Carbon::parse($ex->tanggal_pemeriksaan)->translatedFormat('d M Y'),
                'link' => route('remajas.pemeriksaan', $ex->remaja_id),
            ];

            if ($ex->lila && ($ex->lila < 23.5 || str_contains(strtolower($ex->status_lila ?? ''), 'kek'))) {
                $itemKek = $item;
                $itemKek['detail'] = 'LiLA: ' . $ex->lila . ' cm (' . ($ex->status_lila ?? 'Resiko KEK') . ')';
                $listRemajaKek[] = $itemKek;
            }

            if ($ex->hemoglobin && ($ex->hemoglobin < 12.0 || str_contains(strtolower($ex->status_hb ?? ''), 'anemia'))) {
                $itemAnemia = $item;
                $itemAnemia['detail'] = 'Kadar HB: ' . $ex->hemoglobin . ' g/dL (' . ($ex->status_hb ?? 'Anemia') . ')';
                $listRemajaAnemia[] = $itemAnemia;
            }

            if ($ex->pemberian_ttd === 'ya') {
                $itemTtd = $item;
                $itemTtd['detail'] = 'Pemberian Tablet Tambah Darah (TTD): Ya, Diberikan';
                $listRemajaTtd[] = $itemTtd;
            }
        }

        // 7. Elderly Health Summary (Lansia) with Detailed Person Lists
        $latestLansiaExamsQuery = PemeriksaanLansia::with('lansia.penduduk')
            ->select('pemeriksaan_lansias.*')
            ->join(\DB::raw('(SELECT lansia_id, MAX(tanggal_pemeriksaan) as max_date FROM pemeriksaan_lansias GROUP BY lansia_id) latest'), function($join) {
                $join->on('pemeriksaan_lansias.lansia_id', '=', 'latest.lansia_id')
                     ->on('pemeriksaan_lansias.tanggal_pemeriksaan', '=', 'latest.max_date');
            });
        $latestLansiaExams = $scopePendudukRw($latestLansiaExamsQuery, 'lansia.penduduk')->get();

        $listLansiaHipertensi = [];
        $listLansiaDiabetes = [];
        $listLansiaKolesterol = [];

        foreach ($latestLansiaExams as $ex) {
            $penduduk = $ex->lansia->penduduk ?? null;
            if (!$penduduk) continue;

            $item = [
                'id' => $ex->lansia_id,
                'nama' => $penduduk->nama,
                'nik' => $penduduk->nik,
                'umur' => ($penduduk->umur ?? $penduduk->usia ?? '-') . ' Tahun',
                'wilayah' => ($penduduk->dusun ?? '-') . ' (RW ' . ($penduduk->rw ?? '00') . '/RT ' . ($penduduk->rt ?? '00') . ')',
                'tanggal' => \Carbon\Carbon::parse($ex->tanggal_pemeriksaan)->translatedFormat('d M Y'),
                'link' => route('lansias.pemeriksaan', $ex->lansia_id),
            ];

            if (($ex->tensi_sistolik && $ex->tensi_sistolik >= 140) || ($ex->tensi_diastolik && $ex->tensi_diastolik >= 90) || str_contains(strtolower($ex->status_tensi ?? ''), 'hipertensi')) {
                $itemHip = $item;
                $itemHip['detail'] = 'Tensi: ' . ($ex->tensi_sistolik ?? '-') . '/' . ($ex->tensi_diastolik ?? '-') . ' mmHg (' . ($ex->status_tensi ?? 'Hipertensi') . ')';
                $listLansiaHipertensi[] = $itemHip;
            }

            if (($ex->gula_darah && $ex->gula_darah >= 140) || str_contains(strtolower($ex->status_gula_darah ?? ''), 'diabetes') || str_contains(strtolower($ex->status_gula_darah ?? ''), 'tinggi')) {
                $itemDiab = $item;
                $itemDiab['detail'] = 'Gula Darah: ' . $ex->gula_darah . ' mg/dL (' . ($ex->jenis_gula_darah ?? 'sewaktu') . ' - ' . ($ex->status_gula_darah ?? 'Tinggi') . ')';
                $listLansiaDiabetes[] = $itemDiab;
            }

            if (($ex->kolesterol && $ex->kolesterol >= 200) || str_contains(strtolower($ex->status_kolesterol ?? ''), 'tinggi')) {
                $itemKol = $item;
                $itemKol['detail'] = 'Kolesterol: ' . $ex->kolesterol . ' mg/dL (' . ($ex->status_kolesterol ?? 'Tinggi') . ')';
                $listLansiaKolesterol[] = $itemKol;
            }
        }

        // 8. Analytical Data: Average Baby Weights (Months 1-12)
        $avgBayiWeights = [];
        for ($i = 1; $i <= 12; $i++) {
            $avgQuery = $scopePendudukRw(PemeriksaanBalita::where('umur_bulan', $i), 'bayiBalita.penduduk');
            $avg = $avgQuery->avg("berat_badan");
            $avgBayiWeights[] = $avg ? round($avg, 2) : 0;
        }

        // 9. Analytical Data: Ibu Hamil Risk Factors
        $riskFactorsQuery = function($clauseCallback = null) use ($scopePendudukRw) {
            $q = $scopePendudukRw(IbuHamil::query(), 'penduduk');
            if ($clauseCallback) {
                $clauseCallback($q);
            }
            return $q->count();
        };

        $riskFactors = [
            'Umur <20 atau >35' => $riskFactorsQuery(fn($q) => $q->where('faktor_resiko', 'like', '%umur%')),
            'Anak >4' => $riskFactorsQuery(fn($q) => $q->where('faktor_resiko', 'like', '%anak%')),
            'Jarak Hamil <2 th' => $riskFactorsQuery(fn($q) => $q->where('faktor_resiko', 'like', '%Jarak%')),
            'LILA <23.5 cm' => $riskFactorsQuery(fn($q) => $q->where('faktor_resiko', 'like', '%lila%')),
            'Tinggi <145 cm' => $riskFactorsQuery(fn($q) => $q->where('faktor_resiko', 'like', '%TB%')),
            'Bebas Resiko' => $riskFactorsQuery(fn($q) => $q->where(function($sq) {
                $sq->whereNull('faktor_resiko')->orWhere('faktor_resiko', '');
            })),
        ];

        // 10. Recent Examination Activity Logs
        $recentBalitaExams = $scopePendudukRw(PemeriksaanBalita::with('bayiBalita.penduduk'), 'bayiBalita.penduduk')->orderBy('tanggal_pemeriksaan', 'desc')->take(5)->get();
        $recentLansiaExams = $scopePendudukRw(PemeriksaanLansia::with('lansia.penduduk'), 'lansia.penduduk')->orderBy('tanggal_pemeriksaan', 'desc')->take(5)->get();
        $recentRemajaExams = $scopePendudukRw(PemeriksaanRemaja::with('remaja.penduduk'), 'remaja.penduduk')->orderBy('tanggal_pemeriksaan', 'desc')->take(5)->get();

        return view('admin.dashboard', [
            'title' => 'Dashboard SIP Posyandu',
            'posyanduInfo' => $posyanduInfo,
            'totalPenduduk' => $totalPenduduk,
            'totalIbuHamil' => $totalIbuHamil,
            'totalBayi' => $totalBayi,
            'totalBalita' => $totalBalita,
            'totalRemaja' => $totalRemaja,
            'totalLansia' => $totalLansia,
            'totalWus' => $totalWus,
            'totalPus' => $totalPus,
            'totalPosyandu' => $totalPosyandu,
            'totalKader' => $totalKader,
            'totalBukuTamu' => $totalBukuTamu,
            'totalLaporan' => $totalLaporan,
            'laporanPending' => $laporanPending,
            'ibuHamilExamCount' => $ibuHamilExamCount,
            'bayiExamCount' => $bayiExamCount,
            'balitaExamCount' => $balitaExamCount,
            'remajaExamCount' => $remajaExamCount,
            'lansiaExamCount' => $lansiaExamCount,
            
            // Stunting & Nutrition Lists
            'stuntingCount' => count($listStunting),
            'giziKurangCount' => count($listGiziKurang),
            'giziNormalCount' => count($listGiziNormal),
            'listStunting' => $listStunting,
            'listGiziKurang' => $listGiziKurang,
            'listGiziNormal' => $listGiziNormal,

            // Adolescent Lists
            'remajaKekCount' => count($listRemajaKek),
            'remajaAnemiaCount' => count($listRemajaAnemia),
            'remajaTtdCount' => count($listRemajaTtd),
            'listRemajaKek' => $listRemajaKek,
            'listRemajaAnemia' => $listRemajaAnemia,
            'listRemajaTtd' => $listRemajaTtd,

            // Elderly Lists
            'lansiaHipertensiCount' => count($listLansiaHipertensi),
            'lansiaDiabetesCount' => count($listLansiaDiabetes),
            'lansiaKolesterolCount' => count($listLansiaKolesterol),
            'listLansiaHipertensi' => $listLansiaHipertensi,
            'listLansiaDiabetes' => $listLansiaDiabetes,
            'listLansiaKolesterol' => $listLansiaKolesterol,

            'avgBayiWeights' => $avgBayiWeights,
            'riskFactors' => $riskFactors,
            'recentBalitaExams' => $recentBalitaExams,
            'recentLansiaExams' => $recentLansiaExams,
            'recentRemajaExams' => $recentRemajaExams,
        ]);
    }

        return view('admin.dashboard', [
            'title' => 'Dashboard SIP Posyandu',
            'totalPenduduk' => $totalPenduduk,
            'totalIbuHamil' => $totalIbuHamil,
            'totalBayi' => $totalBayi,
            'totalBalita' => $totalBalita,
            'totalRemaja' => $totalRemaja,
            'totalLansia' => $totalLansia,
            'totalWus' => $totalWus,
            'totalPus' => $totalPus,
            'totalPosyandu' => $totalPosyandu,
            'totalKader' => $totalKader,
            'totalBukuTamu' => $totalBukuTamu,
            'totalLaporan' => $totalLaporan,
            'laporanPending' => $laporanPending,
            'ibuHamilExamCount' => $ibuHamilExamCount,
            'bayiExamCount' => $bayiExamCount,
            'balitaExamCount' => $balitaExamCount,
            'remajaExamCount' => $remajaExamCount,
            'lansiaExamCount' => $lansiaExamCount,
            
            // Stunting & Nutrition Lists
            'stuntingCount' => count($listStunting),
            'giziKurangCount' => count($listGiziKurang),
            'giziNormalCount' => count($listGiziNormal),
            'listStunting' => $listStunting,
            'listGiziKurang' => $listGiziKurang,
            'listGiziNormal' => $listGiziNormal,

            // Adolescent Lists
            'remajaKekCount' => count($listRemajaKek),
            'remajaAnemiaCount' => count($listRemajaAnemia),
            'remajaTtdCount' => count($listRemajaTtd),
            'listRemajaKek' => $listRemajaKek,
            'listRemajaAnemia' => $listRemajaAnemia,
            'listRemajaTtd' => $listRemajaTtd,

            // Elderly Lists
            'lansiaHipertensiCount' => count($listLansiaHipertensi),
            'lansiaDiabetesCount' => count($listLansiaDiabetes),
            'lansiaKolesterolCount' => count($listLansiaKolesterol),
            'listLansiaHipertensi' => $listLansiaHipertensi,
            'listLansiaDiabetes' => $listLansiaDiabetes,
            'listLansiaKolesterol' => $listLansiaKolesterol,

            'avgBayiWeights' => $avgBayiWeights,
            'riskFactors' => $riskFactors,
            'recentBalitaExams' => $recentBalitaExams,
            'recentLansiaExams' => $recentLansiaExams,
            'recentRemajaExams' => $recentRemajaExams,
        ]);
    }
}
