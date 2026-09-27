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
        // 1. Client Projects Table
        Schema::create('client_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('project_code')->unique()->comment('e.g. PRJ-2026-001');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('status', ['discovery', 'in_progress', 'review', 'completed', 'on_hold'])->default('in_progress');
            $table->unsignedTinyInteger('progress_percent')->default(0)->comment('0 to 100');
            $table->string('platform')->nullable()->comment('e.g. Flutter Mobile + Web App, Laravel API');
            $table->decimal('budget', 12, 2)->nullable();
            $table->string('currency', 10)->default('USD');
            $table->date('start_date')->nullable();
            $table->date('delivery_deadline')->nullable();
            $table->string('staging_url')->nullable();
            $table->string('github_repo_url')->nullable();
            $table->string('apk_build_url')->nullable();
            $table->string('figma_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Project Milestones Table
        Schema::create('project_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_project_id')->constrained('client_projects')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // 3. Client Project ↔ Developer Pivot Table
        Schema::create('client_project_developer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_project_id')->constrained('client_projects')->cascadeOnDelete();
            $table->foreignId('developer_id')->constrained('developers')->cascadeOnDelete();
            $table->string('role_in_project')->default('Assigned Engineer');
            $table->timestamps();

            $table->unique(['client_project_id', 'developer_id']);
        });

        // 4. Project Messages & Live Activity Updates Table
        Schema::create('project_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_project_id')->constrained('client_projects')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('sender_type', ['client', 'admin', 'developer', 'system'])->default('client');
            $table->string('sender_name');
            $table->string('sender_avatar')->nullable();
            $table->text('message');
            $table->string('attachment_url')->nullable();
            $table->string('attachment_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_messages');
        Schema::dropIfExists('client_project_developer');
        Schema::dropIfExists('project_milestones');
        Schema::dropIfExists('client_projects');
    }
};
