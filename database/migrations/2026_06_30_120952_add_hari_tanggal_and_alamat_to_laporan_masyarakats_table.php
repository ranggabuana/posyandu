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
        Schema::table('laporan_masyarakats', function (Blueprint $table) {
            $table->date('hari_tanggal')->nullable()->after('kategori');
            $table->text('alamat')->nullable()->after('no_telepon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_masyarakats', function (Blueprint $table) {
            $table->dropColumn(['hari_tanggal', 'alamat']);
        });
    }
};
