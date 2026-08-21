<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('support_conversations')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('direction')->default('in'); // in|out
            $table->string('from_email')->nullable();
            $table->string('to_email')->nullable();
            $table->string('subject')->nullable();
            $table->longText('body')->nullable();
            $table->string('provider')->nullable(); // mailgun|ses|smtp|...
            $table->string('provider_message_id')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
            $table->index(['direction', 'sent_at']);
            $table->index('provider_message_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_messages');
    }
};

