<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ibu_hamils', function (Blueprint $table) {
            $table->date('imunisasi_tt3')->nullable();
            $table->date('imunisasi_tt4')->nullable();
            $table->date('imunisasi_tt5')->nullable();
            $table->string('tablet_tambah_darah_1')->default('Belum');
            $table->string('tablet_tambah_darah_2')->default('Belum');
            $table->string('tablet_tambah_darah_3')->default('Belum');
            for ($i = 1; $i <= 9; $i++) {
                $table->decimal("bb_bulan_{$i}", 5, 2)->nullable();
            }
            $table->date('tgl_melahirkan')->nullable();
            $table->string('ditolong_oleh')->nullable();
            $table->decimal('bb_bayi', 4, 2)->nullable();
            $table->string('jk_bayi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ibu_hamils', function (Blueprint $table) {
            $table->dropColumn([
                'imunisasi_tt3', 'imunisasi_tt4', 'imunisasi_tt5',
                'tablet_tambah_darah_1', 'tablet_tambah_darah_2', 'tablet_tambah_darah_3',
                'bb_bulan_1', 'bb_bulan_2', 'bb_bulan_3', 'bb_bulan_4', 'bb_bulan_5', 'bb_bulan_6', 'bb_bulan_7', 'bb_bulan_8', 'bb_bulan_9',
                'tgl_melahirkan', 'ditolong_oleh', 'bb_bayi', 'jk_bayi'
            ]);
        });
    }
};
