<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('base_rate', 10, 2);
            $table->unsignedTinyInteger('capacity')->default(2);
            $table->timestamps();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
            $table->string('number')->unique();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('floor')->default(1);
            $table->string('status')->default('available');
            $table->boolean('is_visible')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('document_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('room_id')->constrained()->restrictOnDelete();
            $table->foreignId('guest_id')->constrained()->restrictOnDelete();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->unsignedSmallInteger('guests_count')->default(1);
            $table->string('status')->default('pending');
            $table->decimal('nightly_rate', 10, 2);
            $table->decimal('estimated_total', 10, 2)->default(0);
            $table->text('special_requests')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['room_id', 'check_in_date', 'check_out_date']);
            $table->index('status');
        });

        Schema::create('stays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->restrictOnDelete();
            $table->foreignId('guest_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('scheduled');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('stay_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guest_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('draft');
            $table->unsignedSmallInteger('nights')->default(0);
            $table->decimal('room_charges', 10, 2)->default(0);
            $table->decimal('service_charges', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->string('type');
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('stays');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('guests');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_types');
    }
};

