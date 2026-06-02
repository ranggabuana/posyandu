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
        Schema::create('balitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bayi_balita_id')->constrained('bayi_balitas')->cascadeOnDelete();
            $table->string('status_akta')->default('tidak punya'); // 'punya', 'tidak punya'
            
            // Vitamin A (18, 24, 30, 36, 42, 48, 54, 60 bulan)
            $table->string('vitamin_a_18')->nullable()->default('belum');
            $table->string('vitamin_a_24')->nullable()->default('belum');
            $table->string('vitamin_a_30')->nullable()->default('belum');
            $table->string('vitamin_a_36')->nullable()->default('belum');
            $table->string('vitamin_a_42')->nullable()->default('belum');
            $table->string('vitamin_a_48')->nullable()->default('belum');
            $table->string('vitamin_a_54')->nullable()->default('belum');
            $table->string('vitamin_a_60')->nullable()->default('belum');

            // Booster Immunization
            $table->string('booster_dpt_hb_hib')->nullable()->default('belum'); // DPT-Hb-Hib (18-36-bl)
            $table->string('booster_campak')->nullable()->default('belum');     // Campak (24-36 bl)
            $table->text('keterangan_balita')->nullable();

            // Monthly Weights (Month 13 to 60)
            for ($i = 13; $i <= 60; $i++) {
                $table->decimal("bb_bulan_{$i}", 5, 2)->nullable();
            }

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balitas');
    }
};
