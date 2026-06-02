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
        \Illuminate\Support\Facades\DB::statement("SET SESSION sql_mode = ''");

        Schema::table('penduduks', function (Blueprint $table) {
            $table->string('goldar', 5)->nullable()->change();
        });
        Schema::table('bayi_balitas', function (Blueprint $table) {
            $table->string('goldar', 5)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("SET SESSION sql_mode = ''");

        Schema::table('penduduks', function (Blueprint $table) {
            $table->enum('goldar', ['A', 'B', 'AB', 'O'])->nullable()->change();
        });
        Schema::table('bayi_balitas', function (Blueprint $table) {
            $table->enum('goldar', ['A', 'B', 'AB', 'O'])->nullable()->change();
        });
    }
};
