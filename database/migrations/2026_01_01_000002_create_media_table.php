<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('mediable');
            $table->string('disk')->default('public');
            $table->string('collection_name')->default('default');
            $table->string('original_name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('extension')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->text('path');
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('status')->default('active');
            $table->json('custom_properties')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
