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
        Schema::create('laporan_masyarakats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelapor');
            $table->string('nik_pelapor');
            $table->text('isi_laporan');
            $table->string('kategori')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->enum('status', ['baru', 'diproses', 'selesai'])->default('baru');
            $table->text('balasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_masyarakats');
    }
};
