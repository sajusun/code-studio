<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable();
            $table->string('status')->default('open'); // open|pending|closed
            $table->string('customer_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'last_message_at']);
            $table->index('customer_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_conversations');
    }
};

