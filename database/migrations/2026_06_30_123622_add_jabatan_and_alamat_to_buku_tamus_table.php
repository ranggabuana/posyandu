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
        Schema::table('buku_tamus', function (Blueprint $table) {
            $table->string('jabatan')->nullable()->after('instansi');
            $table->text('alamat')->nullable()->after('no_telepon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku_tamus', function (Blueprint $table) {
            $table->dropColumn(['jabatan', 'alamat']);
        });
    }
};
