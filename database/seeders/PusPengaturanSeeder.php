<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PusPengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengaturans = [
            ['key' => 'pus_umur_min', 'value' => '15', 'label' => 'Batas Umur Minimal PUS', 'keterangan' => 'Dalam satuan tahun'],
            ['key' => 'pus_umur_max', 'value' => '49', 'label' => 'Batas Umur Maksimal PUS', 'keterangan' => 'Dalam satuan tahun'],
        ];

        // Insert ignoring duplicates if they already exist
        foreach ($pengaturans as $p) {
            DB::table('pengaturans')->updateOrInsert(
                ['key' => $p['key']],
                $p
            );
        }
    }
}
