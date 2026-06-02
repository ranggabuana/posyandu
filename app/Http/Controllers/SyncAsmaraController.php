<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncAsmaraController extends Controller
{
    public function sync()
    {
        try {
            $apiUrl = config('services.asmara.url');
            $apiKey = config('services.asmara.key');
            $apiSecret = config('services.asmara.secret');

            $response = Http::withBasicAuth($apiKey, $apiSecret)
                ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36')
                ->acceptJson()
                ->timeout(120)
                ->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();

                $syncedCount = 0;

                foreach ($data as $item) {
                    $kelamin = strtolower(trim($item['kelamin'] ?? ''));
                    if (!in_array($kelamin, ['laki-laki', 'perempuan'])) {
                        $kelamin = 'laki-laki';
                    }

                    $status_kawin = strtolower(trim($item['status_kawin'] ?? 'belum kawin'));
                    if (!in_array($status_kawin, ['belum kawin', 'kawin', 'cerai hidup', 'cerai mati'])) {
                        $status_kawin = 'belum kawin';
                    }

                    $goldar = trim($item['goldar'] ?? '');
                    if (strlen($goldar) > 5 || strtoupper($goldar) === 'TIDAK TAHU') {
                        $goldar = '-';
                    }

                    Penduduk::updateOrCreate(
                        ['nik' => $item['nik']],
                        [
                            'nama' => $item['nama'] ?? '-',
                            'no_kk' => $item['no_kk'] ?? '-',
                            'nama_kk' => $item['nama_kk'] ?? '-',
                            'hubungan_keluarga' => $item['hubungan_keluarga'] ?? '-',
                            'kelamin' => $kelamin,
                            'tempatlahir' => $item['tempatlahir'] ?? '-',
                            'tanggallahir' => !empty($item['tanggallahir']) ? $item['tanggallahir'] : now()->toDateString(),
                            'agama' => $item['agama'],
                            'pendidikan' => $item['pendidikan'],
                            'pekerjaan' => $item['pekerjaan'],
                            'status_kawin' => $status_kawin,
                            'nama_ayah' => $item['nama_ayah'],
                            'nama_ibu' => $item['nama_ibu'],
                            'goldar' => $goldar,
                            'alamat' => $item['alamat'],
                            'rw' => $item['rw'],
                            'rt' => $item['rt'],
                            'dusun' => $item['dusun'],
                            'telepon' => $item['telepon'],
                            'bpjs' => !empty($item['bpjs_ketenagakerjaan']) ? true : false,
                        ]
                    );
                    $syncedCount++;
                }

                return back()->with('success', "Berhasil sinkronisasi $syncedCount data penduduk dari OpenSID.");
            }

            return back()->with('error', 'Gagal mengambil data dari OpenSID: ' . $response->status());
        } catch (\Exception $e) {
            Log::error('Error sync asmara: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat sinkronisasi data: ' . $e->getMessage());
        }
    }
}
