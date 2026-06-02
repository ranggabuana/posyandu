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
        Schema::create('ibu_hamils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->constrained('penduduks')->cascadeOnDelete();
            $table->integer('usia_kehamilan_minggu')->nullable();
            $table->date('hpht')->nullable();
            $table->date('taksiran_persalinan')->nullable();
            $table->integer('jumlah_kunjungan')->default(0);
            $table->enum('status', ['aktif', 'selesai', 'gugur'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibu_hamils');
    }
};
