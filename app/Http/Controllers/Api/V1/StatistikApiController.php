<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\BayiBalita;
use App\Models\Berita;
use App\Models\BukuTamu;
use App\Models\Galeri;
use App\Models\IbuHamil;
use App\Models\Jadwal;
use App\Models\Kader;
use App\Models\Lansia;
use App\Models\LaporanMasyarakat;
use App\Models\Penduduk;
use App\Models\Posyandu;
use App\Models\Pus;
use App\Models\Remaja;
use App\Models\Tim;
use App\Models\Wus;
use App\Services\ApiSettingsService;
use Illuminate\Http\JsonResponse;

class StatistikApiController extends BaseApiController
{
    public function health(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'SIP Posyandu REST API berjalan normal.',
            'data' => [
                'status' => ApiSettingsService::get('api_enabled', '1') === '1' ? 'active' : 'disabled',
                'version' => 'v1',
                'auth_required' => ApiSettingsService::get('api_auth_required', '1') === '1',
                'server_time' => now()->toIso8601String(),
            ],
        ]);
    }

    public function statistik(): JsonResponse
    {
        $totalBayi = BayiBalita::whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) <= 12')->count();
        $totalBalita = BayiBalita::whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) > 12 AND TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) <= 60')->count();

        $data = [
            'kependudukan' => [
                'total_penduduk' => Penduduk::count(),
                'laki_laki' => Penduduk::where('kelamin', 'laki-laki')->count(),
                'perempuan' => Penduduk::where('kelamin', 'perempuan')->count(),
            ],
            'kesehatan' => [
                'ibu_hamil' => IbuHamil::count(),
                'bayi_0_12_bulan' => $totalBayi,
                'balita_13_60_bulan' => $totalBalita,
                'total_bayi_balita' => BayiBalita::count(),
                'remaja' => Remaja::count(),
                'lansia' => Lansia::count(),
                'wus' => Wus::count(),
                'pus' => Pus::count(),
            ],
            'kelembagaan' => [
                'posyandu' => Posyandu::count(),
                'kader' => Kader::count(),
                'kader_aktif' => Kader::where('status_aktif', true)->count(),
            ],
            'konten_dan_interaksi' => [
                'berita' => Berita::count(),
                'galeri' => Galeri::count(),
                'jadwal' => Jadwal::count(),
                'tim' => Tim::count(),
                'buku_tamu' => BukuTamu::count(),
                'laporan_masyarakat' => LaporanMasyarakat::count(),
            ],
        ];

        return $this->successResponse($data, 'Berhasil mengambil statistik lengkap SIP Posyandu.');
    }
}

