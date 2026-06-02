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
        Schema::create('penduduks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_kk');
            $table->string('nik')->unique();
            $table->string('nama_kk');
            $table->string('hubungan_keluarga');
            $table->enum('kelamin', ['laki-laki', 'perempuan']);
            $table->string('tempatlahir');
            $table->date('tanggallahir');
            $table->string('agama')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->enum('status_kawin', ['belum kawin', 'kawin', 'cerai hidup', 'cerai mati']);
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->enum('goldar', ['A', 'B', 'AB', 'O'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('rw')->nullable();
            $table->string('rt')->nullable();
            $table->string('dusun')->nullable();
            $table->string('telepon')->nullable();
            $table->boolean('bpjs')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduks');
    }
};
