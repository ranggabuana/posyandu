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
        Schema::table('bayi_balitas', function (Blueprint $table) {
            $table->string('imunisasi_hbo_kurang_7_hari')->nullable();
            $table->string('imunisasi_hbo_lebih_7_hari')->nullable();
            $table->string('imunisasi_bcg_polio1')->nullable();
            $table->string('imunisasi_pentavalen1_polio2')->nullable();
            $table->string('imunisasi_pentavalen2_polio3')->nullable();
            $table->string('imunisasi_pentavalen3_polio4')->nullable();
            $table->text('imunisasi_keterangan')->nullable();
            for ($i = 1; $i <= 12; $i++) {
                $table->decimal("bb_bulan_{$i}", 5, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bayi_balitas', function (Blueprint $table) {
            $table->dropColumn([
                'imunisasi_hbo_kurang_7_hari',
                'imunisasi_hbo_lebih_7_hari',
                'imunisasi_bcg_polio1',
                'imunisasi_pentavalen1_polio2',
                'imunisasi_pentavalen2_polio3',
                'imunisasi_pentavalen3_polio4',
                'imunisasi_keterangan',
            ]);
            for ($i = 1; $i <= 12; $i++) {
                $table->dropColumn("bb_bulan_{$i}");
            }
        });
    }
};
