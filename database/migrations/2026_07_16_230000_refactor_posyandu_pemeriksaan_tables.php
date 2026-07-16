<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create pemeriksaan_balitas
        Schema::create('pemeriksaan_balitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bayi_balita_id')->constrained('bayi_balitas')->cascadeOnDelete();
            $table->date('tanggal_pemeriksaan');
            $table->integer('umur_bulan');
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->decimal('lingkar_lengan_atas', 5, 2)->nullable();
            $table->decimal('lingkar_kepala', 5, 2)->nullable();
            $table->string('asi_eksklusif')->nullable();
            $table->string('vitamin_a')->nullable(); // 'merah', 'biru', 'tidak'
            $table->boolean('obat_cacing')->default(false);
            $table->boolean('pmt')->default(false);
            $table->string('status_gizi_bb_u')->nullable();
            $table->string('status_gizi_tb_u')->nullable();
            $table->string('status_gizi_bb_tb')->nullable();
            $table->text('catatan_perkembangan')->nullable();
            $table->timestamps();
        });

        // 2. Create imunisasi_balitas
        Schema::create('imunisasi_balitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bayi_balita_id')->constrained('bayi_balitas')->cascadeOnDelete();
            $table->string('nama_vaksin');
            $table->date('tanggal_pemberian');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 3. Add status_akta to bayi_balitas
        Schema::table('bayi_balitas', function (Blueprint $table) {
            $table->string('status_akta')->default('tidak punya');
        });

        // 4. Data Migration
        // Process bayi_balitas (0-12 months)
        $bayis = DB::table('bayi_balitas')->get();
        foreach ($bayis as $bayi) {
            $dob = $bayi->tanggal_lahir ? Carbon::parse($bayi->tanggal_lahir) : null;
            $createdAt = Carbon::parse($bayi->created_at);

            // Migrasi BB, TB, LLA, LK bulanan
            for ($i = 1; $i <= 12; $i++) {
                $bbCol = "bb_bulan_{$i}";
                $tbCol = "tb_bulan_{$i}";
                $llaCol = "lla_bulan_{$i}";
                $lkCol = "lk_bulan_{$i}";

                $bb = property_exists($bayi, $bbCol) ? $bayi->$bbCol : null;
                $tb = property_exists($bayi, $tbCol) ? $bayi->$tbCol : null;
                $lla = property_exists($bayi, $llaCol) ? $bayi->$llaCol : null;
                $lk = property_exists($bayi, $lkCol) ? $bayi->$lkCol : null;

                if ($bb !== null || $tb !== null || $lla !== null || $lk !== null) {
                    $examDate = $dob ? $dob->copy()->addMonths($i)->toDateString() : $createdAt->toDateString();
                    DB::table('pemeriksaan_balitas')->insert([
                        'bayi_balita_id' => $bayi->id,
                        'tanggal_pemeriksaan' => $examDate,
                        'umur_bulan' => $i,
                        'berat_badan' => $bb,
                        'tinggi_badan' => $tb,
                        'lingkar_lengan_atas' => $lla,
                        'lingkar_kepala' => $lk,
                        'asi_eksklusif' => $i <= 6 ? 'Ya' : 'Tidak',
                        'created_at' => $bayi->created_at,
                        'updated_at' => $bayi->updated_at,
                    ]);
                }
            }

            // Migrasi Imunisasi Bayi
            $imunisasis = [
                'imunisasi_hbo_kurang_7_hari' => 'HBO < 7 Hari',
                'imunisasi_hbo_lebih_7_hari' => 'HBO > 7 Hari',
                'imunisasi_bcg_polio1' => 'BCG & Polio 1',
                'imunisasi_pentavalen1_polio2' => 'Pentavalen 1 & Polio 2',
                'imunisasi_pentavalen2_polio3' => 'Pentavalen 2 & Polio 3',
                'imunisasi_pentavalen3_polio4' => 'Pentavalen 3 & Polio 4',
            ];

            foreach ($imunisasis as $col => $name) {
                $val = property_exists($bayi, $col) ? $bayi->$col : null;
                if ($val && ($val === 'L' || $val === 'P' || $val === 'sudah' || $val === 'Sudah')) {
                    $giveDate = $dob ? $dob->copy()->addDays(7)->toDateString() : $createdAt->toDateString();
                    DB::table('imunisasi_balitas')->insert([
                        'bayi_balita_id' => $bayi->id,
                        'nama_vaksin' => $name,
                        'tanggal_pemberian' => $giveDate,
                        'keterangan' => property_exists($bayi, 'imunisasi_keterangan') ? $bayi->imunisasi_keterangan : null,
                        'created_at' => $bayi->created_at,
                        'updated_at' => $bayi->updated_at,
                    ]);
                }
            }
        }

        // Process balitas (13-60 months)
        if (Schema::hasTable('balitas')) {
            $balitas = DB::table('balitas')->get();
            foreach ($balitas as $balita) {
                // Update status_akta di bayi_balitas
                DB::table('bayi_balitas')
                    ->where('id', $balita->bayi_balita_id)
                    ->update(['status_akta' => $balita->status_akta ?? 'tidak punya']);

                $bayi = DB::table('bayi_balitas')->where('id', $balita->bayi_balita_id)->first();
                if (!$bayi) continue;

                $dob = $bayi->tanggal_lahir ? Carbon::parse($bayi->tanggal_lahir) : null;
                $createdAt = Carbon::parse($balita->created_at);

                // Migrasi BB, TB, LLA, LK bulanan (13-60)
                for ($i = 13; $i <= 60; $i++) {
                    $bbCol = "bb_bulan_{$i}";
                    $tbCol = "tb_bulan_{$i}";
                    $llaCol = "lla_bulan_{$i}";
                    $lkCol = "lk_bulan_{$i}";

                    $bb = property_exists($balita, $bbCol) ? $balita->$bbCol : null;
                    $tb = property_exists($balita, $tbCol) ? $balita->$tbCol : null;
                    $lla = property_exists($balita, $llaCol) ? $balita->$llaCol : null;
                    $lk = property_exists($balita, $lkCol) ? $balita->$lkCol : null;

                    if ($bb !== null || $tb !== null || $lla !== null || $lk !== null) {
                        $examDate = $dob ? $dob->copy()->addMonths($i)->toDateString() : $createdAt->toDateString();
                        
                        // Cek apakah data Vitamin A diberikan pada bulan ini
                        $vit = null;
                        if ($i % 6 === 0 && in_array($i, [18, 24, 30, 36, 42, 48, 54, 60])) {
                            $vitCol = "vitamin_a_{$i}";
                            $vitVal = property_exists($balita, $vitCol) ? $balita->$vitCol : 'belum';
                            if ($vitVal === 'sudah' || $vitVal === 'Sudah') {
                                $vit = 'merah';
                            }
                        }

                        DB::table('pemeriksaan_balitas')->insert([
                            'bayi_balita_id' => $balita->bayi_balita_id,
                            'tanggal_pemeriksaan' => $examDate,
                            'umur_bulan' => $i,
                            'berat_badan' => $bb,
                            'tinggi_badan' => $tb,
                            'lingkar_lengan_atas' => $lla,
                            'lingkar_kepala' => $lk,
                            'vitamin_a' => $vit,
                            'catatan_perkembangan' => property_exists($balita, 'keterangan_balita') ? $balita->keterangan_balita : null,
                            'created_at' => $balita->created_at,
                            'updated_at' => $balita->updated_at,
                        ]);
                    }
                }

                // Migrasi Booster Imunisasi
                $boosters = [
                    'booster_dpt_hb_hib' => 'DPT-HB-Hib Booster',
                    'booster_campak' => 'Campak/MR Booster',
                ];

                foreach ($boosters as $col => $name) {
                    $val = property_exists($balita, $col) ? $balita->$col : null;
                    if ($val === 'sudah' || $val === 'Sudah') {
                        $giveDate = $dob ? $dob->copy()->addMonths(18)->toDateString() : $createdAt->toDateString();
                        DB::table('imunisasi_balitas')->insert([
                            'bayi_balita_id' => $balita->bayi_balita_id,
                            'nama_vaksin' => $name,
                            'tanggal_pemberian' => $giveDate,
                            'created_at' => $balita->created_at,
                            'updated_at' => $balita->updated_at,
                        ]);
                    }
                }
            }

            // Drop balitas table
            Schema::dropIfExists('balitas');
        }

        // 5. Drop old monthly & immunization columns from bayi_balitas
        Schema::table('bayi_balitas', function (Blueprint $table) {
            $table->dropColumn([
                'imunisasi_hbo_kurang_7_hari',
                'imunisasi_hbo_lebih_7_hari',
                'imunisasi_bcg_polio1',
                'imunisasi_pentavalen1_polio2',
                'imunisasi_pentavalen2_polio3',
                'imunisasi_pentavalen3_polio4',
                'imunisasi_keterangan'
            ]);
            for ($i = 1; $i <= 12; $i++) {
                $table->dropColumn([
                    "bb_bulan_{$i}",
                    "tb_bulan_{$i}",
                    "lla_bulan_{$i}",
                    "lk_bulan_{$i}"
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imunisasi_balitas');
        Schema::dropIfExists('pemeriksaan_balitas');
        
        if (Schema::hasTable('bayi_balitas')) {
            Schema::table('bayi_balitas', function (Blueprint $table) {
                $table->dropColumn('status_akta');
            });
        }
    }
};
