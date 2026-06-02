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
        Schema::table('posyandus', function (Blueprint $table) {
            $table->string('ketua')->nullable()->after('nama');
            $table->string('telepon')->nullable()->after('alamat');
            $table->string('jadwal_rutin')->nullable()->after('telepon');
            $table->string('lokasi_koordinat')->nullable()->after('jadwal_rutin');
            
            // Sarana Prasarana & Operasional
            $table->integer('jumlah_kader')->default(0)->after('rw_diampu');
            $table->string('bangunan')->nullable()->comment('Milik sendiri/Sewa/Numpang')->after('jumlah_kader');
            $table->boolean('punya_timbangan_dacin')->default(false)->after('bangunan');
            $table->boolean('punya_timbangan_digital')->default(false)->after('punya_timbangan_dacin');
            $table->boolean('punya_alat_ukur_tinggi')->default(false)->after('punya_timbangan_digital');
            $table->boolean('punya_pita_liLa')->default(false)->after('punya_alat_ukur_tinggi');
            $table->boolean('punya_buku_kia')->default(false)->after('punya_pita_liLa');
            $table->text('keterangan_lain')->nullable()->after('punya_buku_kia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posyandus', function (Blueprint $table) {
            $table->dropColumn([
                'ketua', 'telepon', 'jadwal_rutin', 'lokasi_koordinat',
                'jumlah_kader', 'bangunan', 'punya_timbangan_dacin',
                'punya_timbangan_digital', 'punya_alat_ukur_tinggi',
                'punya_pita_liLa', 'punya_buku_kia', 'keterangan_lain'
            ]);
        });
    }
};
