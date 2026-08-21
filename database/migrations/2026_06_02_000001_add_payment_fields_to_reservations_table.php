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
        Schema::table('reservations', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'on_arrival'])
                ->default('pending')
                ->after('estimated_total');
            $table->enum('payment_method', ['stripe', 'paypal', 'on_arrival'])
                ->nullable()
                ->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_method']);
        });
    }
};
