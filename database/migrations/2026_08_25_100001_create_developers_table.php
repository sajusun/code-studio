<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('developers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('phone', 30)->nullable();
            $table->text('bio')->nullable();
            $table->string('github_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('twitter_url')->nullable();
            // Skills as JSON array (e.g. ["Laravel","Vue","MySQL"])
            $table->json('skills')->nullable();
            // Years of experience
            $table->unsignedTinyInteger('experience_years')->default(0);
            // Status: published = show on public site, private = admin only, draft = WIP
            $table->enum('status', ['published', 'private', 'draft'])->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developers');
    }
};
