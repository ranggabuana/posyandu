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
        if (!Schema::hasColumn('ibu_hamils', 'posyandu_id')) {
            Schema::table('ibu_hamils', function (Blueprint $table) {
                $table->foreignId('posyandu_id')->nullable()->after('penduduk_id')->constrained('posyandus')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('ibu_hamils', 'posyandu_id')) {
            Schema::table('ibu_hamils', function (Blueprint $table) {
                $table->dropForeign(['posyandu_id']);
                $table->dropColumn('posyandu_id');
            });
        }
    }
};
