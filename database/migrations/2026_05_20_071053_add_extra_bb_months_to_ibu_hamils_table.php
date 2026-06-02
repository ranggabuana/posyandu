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
            $table->decimal('bb_bulan_10', 5, 2)->nullable();
            $table->decimal('bb_bulan_11', 5, 2)->nullable();
            $table->decimal('bb_bulan_12', 5, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ibu_hamils', function (Blueprint $table) {
            $table->dropColumn(['bb_bulan_10', 'bb_bulan_11', 'bb_bulan_12']);
        });
    }
};
