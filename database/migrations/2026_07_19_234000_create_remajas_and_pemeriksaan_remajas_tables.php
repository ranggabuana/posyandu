<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->constrained('penduduks')->cascadeOnDelete();
            $table->foreignId('posyandu_id')->nullable()->constrained('posyandus')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('pemeriksaan_remajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remaja_id')->constrained('remajas')->cascadeOnDelete();
            $table->date('tanggal_pemeriksaan');
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->decimal('imt', 4, 2)->nullable();
            $table->string('status_imt')->nullable();
            $table->decimal('lila', 4, 1)->nullable(); // Lingkar Lengan Atas (cm)
            $table->string('status_lila')->nullable();
            $table->decimal('lingkar_perut', 5, 2)->nullable();
            $table->integer('tensi_sistolik')->nullable();
            $table->integer('tensi_diastolik')->nullable();
            $table->string('status_tensi')->nullable();
            $table->decimal('hemoglobin', 4, 1)->nullable(); // HB (g/dL)
            $table->string('status_hb')->nullable(); // Anemia Berat, Sedang, Ringan, Normal
            $table->integer('gula_darah')->nullable();
            $table->string('jenis_gula_darah')->default('sewaktu');
            $table->string('status_gula_darah')->nullable();
            $table->string('pemberian_ttd')->default('tidak'); // Tablet Tambah Darah: ya / tidak
            $table->text('keluhan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_remajas');
        Schema::dropIfExists('remajas');
    }
};
