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
        Schema::create('bayi_balitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->constrained('penduduks')->cascadeOnDelete();
            $table->string('nama_ibu')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->decimal('berat_lahir', 5, 2)->nullable();
            $table->decimal('panjang_lahir', 5, 2)->nullable();
            $table->enum('goldar', ['A', 'B', 'AB', 'O'])->nullable();
            $table->boolean('bpjs')->default(false);
            $table->foreignId('posyandu_id')->nullable()->constrained('posyandus')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bayi_balitas');
    }
};
