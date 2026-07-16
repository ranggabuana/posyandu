<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\IbuHamil;
use App\Models\BayiBalita;
use App\Models\PemeriksaanBalita;
use App\Models\Lansia;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $totalIbuHamil = IbuHamil::count();
        $totalBayi = BayiBalita::whereRaw("TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) <= 12")->count();
        $totalBalita = BayiBalita::whereRaw("TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) > 12")
            ->whereRaw("TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) <= 60")
            ->count();
        $totalLansia = Lansia::count();

        // Calculate examination logs (non-null weight entries)
        $ibuHamilExamCount = 0;
        for ($i = 1; $i <= 12; $i++) {
            $ibuHamilExamCount += IbuHamil::whereNotNull("bb_bulan_{$i}")->count();
        }

        $bayiExamCount = PemeriksaanBalita::where('umur_bulan', '<=', 12)->count();
        $balitaExamCount = PemeriksaanBalita::where('umur_bulan', '>', 12)->where('umur_bulan', '<=', 60)->count();

        // Analytical Data 1: Average Baby Weights (Months 1-12)
        $avgBayiWeights = [];
        for ($i = 1; $i <= 12; $i++) {
            $avg = PemeriksaanBalita::where('umur_bulan', $i)->avg("berat_badan");
            $avgBayiWeights[] = $avg ? round($avg, 2) : 0;
        }

        // Analytical Data 2: Ibu Hamil Risk Factors
        $riskFactors = [
            'Umur <20 atau >35' => IbuHamil::where('faktor_resiko', 'like', '%umur%')->count(),
            'Anak >4' => IbuHamil::where('faktor_resiko', 'like', '%anak%')->count(),
            'Jarak Hamil <2 th' => IbuHamil::where('faktor_resiko', 'like', '%Jarak%')->count(),
            'LILA <23.5 cm' => IbuHamil::where('faktor_resiko', 'like', '%lila%')->count(),
            'Tinggi <145 cm' => IbuHamil::where('faktor_resiko', 'like', '%TB%')->count(),
            'Bebas Resiko' => IbuHamil::where(function($q) {
                $q->whereNull('faktor_resiko')->orWhere('faktor_resiko', '');
            })->count(),
        ];

        return view('admin.dashboard', [
            'title' => 'Tuku Siki | Dashboard',
            'totalIbuHamil' => $totalIbuHamil,
            'totalBayi' => $totalBayi,
            'totalBalita' => $totalBalita,
            'totalLansia' => $totalLansia,
            'ibuHamilExamCount' => $ibuHamilExamCount,
            'bayiExamCount' => $bayiExamCount,
            'balitaExamCount' => $balitaExamCount,
            'avgBayiWeights' => $avgBayiWeights,
            'riskFactors' => $riskFactors,
        ]);
    }
}
