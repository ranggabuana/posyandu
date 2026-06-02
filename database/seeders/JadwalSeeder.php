<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwals = [
            [
                'hari_tanggal' => 'Senin, Minggu ke-1',
                'jam_mulai' => '07:30',
                'jam_selesai' => '11:00',
                'kegiatan' => 'Bayi & Balita',
                'keterangan' => 'Pemantauan tumbuh kembang dan penimbangan balita rutin.'
            ],
            [
                'hari_tanggal' => 'Rabu, Minggu ke-1',
                'jam_mulai' => '08:00',
                'jam_selesai' => '11:30',
                'kegiatan' => 'Ibu Hamil',
                'keterangan' => 'Pemeriksaan kehamilan (ANC) dan konseling ibu hamil.'
            ],
            [
                'hari_tanggal' => 'Jumat, Minggu ke-2',
                'jam_mulai' => '08:00',
                'jam_selesai' => '12:00',
                'kegiatan' => 'Imunisasi',
                'keterangan' => 'Layanan imunisasi dasar untuk bayi sesuai jadwal.'
            ],
            [
                'hari_tanggal' => 'Senin, Minggu ke-3',
                'jam_mulai' => '08:00',
                'jam_selesai' => '11:00',
                'kegiatan' => 'Lansia',
                'keterangan' => 'Pemeriksaan tensi, gula darah, dan konsultasi kesehatan lansia.'
            ],
            [
                'hari_tanggal' => 'Kamis, Minggu ke-4',
                'jam_mulai' => '09:00',
                'jam_selesai' => '12:00',
                'kegiatan' => 'Penyuluhan & Gizi',
                'keterangan' => 'Edukasi kesehatan masyarakat dan konsultasi gizi balita.'
            ],
        ];

        foreach ($jadwals as $jadwal) {
            \App\Models\Jadwal::create($jadwal);
        }
    }
}
