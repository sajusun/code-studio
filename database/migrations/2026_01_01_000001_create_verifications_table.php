<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('verification_type')->default('otp'); // 'otp' | 'token'
            $table->string('purpose'); // 'email_verification', 'password_reset', 'phone_verification'
            $table->string('code'); // Hashed token or numeric OTP
            $table->unsignedInteger('attempts')->default(0);
            $table->unsignedInteger('request_count')->default(1);
            $table->timestamp('last_requested_at')->nullable();
            $table->timestamp('blocked_until')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->enum('status', ['pending', 'verified', 'expired', 'failed'])->default('pending');
            $table->timestamps();

            $table->index(['user_id', 'purpose', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifications');
    }
};
