<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemeriksaan_lansias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lansia_id')->constrained('lansias')->cascadeOnDelete();
            $table->date('tanggal_pemeriksaan');
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->decimal('lingkar_perut', 5, 2)->nullable();
            $table->integer('tensi_sistolik')->nullable();
            $table->integer('tensi_diastolik')->nullable();
            $table->integer('gula_darah')->nullable();
            $table->string('jenis_gula_darah')->default('sewaktu');
            $table->integer('kolesterol')->nullable();
            $table->decimal('asam_urat', 4, 1)->nullable();
            $table->decimal('imt', 4, 2)->nullable();
            $table->string('status_imt')->nullable();
            $table->string('status_tensi')->nullable();
            $table->string('status_gula_darah')->nullable();
            $table->string('status_kolesterol')->nullable();
            $table->string('status_asam_urat')->nullable();
            $table->text('keluhan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_lansias');
    }
};
