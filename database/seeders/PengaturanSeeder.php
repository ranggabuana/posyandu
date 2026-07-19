<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengaturans = [
            ['key' => 'umur_lansia_min', 'value' => '60', 'label' => 'Batas Umur Minimal Lansia', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'remaja_umur_min', 'value' => '10', 'label' => 'Batas Umur Minimal Remaja', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'remaja_umur_max', 'value' => '18', 'label' => 'Batas Umur Maksimal Remaja', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'wus_umur_min', 'value' => '15', 'label' => 'Batas Umur Minimal WUS', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'wus_umur_max', 'value' => '49', 'label' => 'Batas Umur Maksimal WUS', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'pus_umur_min', 'value' => '15', 'label' => 'Batas Umur Minimal PUS', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'pus_umur_max', 'value' => '49', 'label' => 'Batas Umur Maksimal PUS', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'nama_aplikasi', 'value' => 'Posyandu Melati Sehat', 'label' => 'Nama Aplikasi', 'keterangan' => 'Nama aplikasi yang ditampilkan di header'],
            ['key' => 'nama_desa', 'value' => 'Desa Banjar', 'label' => 'Nama Desa', 'keterangan' => 'Nama desa/kelurahan'],
            ['key' => 'alamat_desa', 'value' => 'Jl. Raya Banjar No. 1, Desa Banjar', 'label' => 'Alamat Desa', 'keterangan' => 'Alamat kantor desa'],
            ['key' => 'moto', 'value' => 'Melayani dengan Hati, Menjaga Kesehatan Komunitas.', 'label' => 'Moto / Slogan', 'keterangan' => 'Slogan yang tampil di footer/hero'],
            ['key' => 'email', 'value' => 'posyandu.melati@gmail.com', 'label' => 'Email Kontak', 'keterangan' => 'Email resmi posyandu'],
            ['key' => 'no_whatsapp', 'value' => '6285123456789', 'label' => 'Nomor WhatsApp', 'keterangan' => 'Nomor WA aktif kader (awali dengan 62)'],
            ['key' => 'logo_desa', 'value' => '', 'label' => 'Logo Desa', 'keterangan' => 'Path file logo'],
            ['key' => 'jam_operasional', 'value' => "Senin – Jumat: 07:30 – 12:00\nSabtu: 07:30 – 11:00\nMinggu & Libur: Tutup", 'label' => 'Jam Operasional', 'keterangan' => 'Jam operasional pelayanan (pisahkan baris dengan Enter)'],
        ];

        foreach ($pengaturans as $p) {
            DB::table('pengaturans')->updateOrInsert(
                ['key' => $p['key']],
                $p
            );
        }
    }
}
