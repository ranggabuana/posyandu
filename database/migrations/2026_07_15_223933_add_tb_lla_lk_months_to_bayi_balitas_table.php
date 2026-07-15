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
            for ($i = 1; $i <= 12; $i++) {
                $table->decimal("tb_bulan_{$i}", 5, 2)->nullable();
                $table->decimal("lla_bulan_{$i}", 5, 2)->nullable();
                $table->decimal("lk_bulan_{$i}", 5, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bayi_balitas', function (Blueprint $table) {
            for ($i = 1; $i <= 12; $i++) {
                $table->dropColumn("tb_bulan_{$i}");
                $table->dropColumn("lla_bulan_{$i}");
                $table->dropColumn("lk_bulan_{$i}");
            }
        });
    }
};
