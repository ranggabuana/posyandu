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
        Schema::create('kaders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('posyandu_id')->constrained('posyandus')->onDelete('cascade');
            $table->foreignId('penduduk_id')->constrained('penduduks')->onDelete('cascade');
            $table->string('jabatan');
            $table->date('tanggal_mulai_tugas')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->text('pelatihan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kaders');
    }
};
