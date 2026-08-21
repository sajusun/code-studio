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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['web_app', 'android', 'ios', 'custom'])->default('web_app');
            $table->string('category')->default('General');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            
            // Demos & Media
            $table->string('demo_url')->nullable();
            $table->string('admin_demo_url')->nullable();
            $table->string('admin_demo_username')->nullable();
            $table->string('admin_demo_password')->nullable();
            $table->string('video_url')->nullable();
            $table->string('apk_url')->nullable();
            $table->string('testflight_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('screenshots')->nullable();
            
            // Tech Stack & Features
            $table->json('tech_stack')->nullable();
            $table->json('features')->nullable();
            
            // Contact Channels & Lead Settings
            $table->json('contact_channels')->nullable();
            $table->boolean('allow_custom_quotes')->default(true);
            
            // Flags
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
