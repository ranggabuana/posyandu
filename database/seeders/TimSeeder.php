<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tims = [
            [
                'nama' => 'dr. Sari Dewi',
                'jabatan' => 'Dokter Koordinator',
                'deskripsi' => '15 tahun pengalaman di bidang kesehatan ibu dan anak. Lulusan FK UGM.',
                'foto' => null,
            ],
            [
                'nama' => 'Bd. Rina Wulandari',
                'jabatan' => 'Bidan Desa',
                'deskripsi' => 'Spesialis KIA dengan 10 tahun pengalaman mendampingi ibu hamil dan menyusui.',
                'foto' => null,
            ],
            [
                'nama' => 'Ns. Agus Santoso',
                'jabatan' => 'Perawat Komunitas',
                'deskripsi' => 'Ahli kesehatan lansia dan pengelolaan penyakit tidak menular di komunitas.',
                'foto' => null,
            ],
            [
                'nama' => 'Bu Siti Aminah',
                'jabatan' => 'Ketua Kader',
                'deskripsi' => 'Kader senior yang telah mengabdi sejak 2008. Motor penggerak posyandu.',
                'foto' => null,
            ],
        ];

        foreach ($tims as $tim) {
            \App\Models\Tim::create($tim);
        }
    }
}
