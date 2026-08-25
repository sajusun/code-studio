<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('developer_developer_role', function (Blueprint $table) {
            $table->foreignId('developer_id')
                ->constrained('developers')
                ->cascadeOnDelete();
            $table->foreignId('developer_role_id')
                ->constrained('developer_roles')
                ->cascadeOnDelete();
            $table->primary(['developer_id', 'developer_role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developer_developer_role');
    }
};
