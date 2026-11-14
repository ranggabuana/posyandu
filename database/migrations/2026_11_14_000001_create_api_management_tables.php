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
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('key_prefix', 24);
            $table->string('key_hash', 64)->unique();
            $table->json('abilities')->nullable();
            $table->text('allowed_ips')->nullable();
            $table->unsignedInteger('rate_limit')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->string('last_used_ip', 45)->nullable();
            $table->unsignedBigInteger('hit_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_key_id')->nullable()->constrained('api_keys')->nullOnDelete();
            $table->string('method', 10);
            $table->string('endpoint');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->unsignedSmallInteger('status_code');
            $table->unsignedInteger('response_time_ms')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['created_at', 'status_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
        Schema::dropIfExists('api_keys');
    }
};

