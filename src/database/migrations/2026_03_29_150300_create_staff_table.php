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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();

            // Basic information
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Position and role
            $table->enum('position', ['admin', 'manager', 'cook', 'delivery', 'cashier']);
            $table->text('responsibilities')->nullable(); // JSON of duties

            // Employment details
            $table->date('hire_date');
            $table->integer('salary')->nullable();
            $table->string('work_schedule')->nullable(); // JSON of working hours
            $table->boolean('is_active')->default(true);

            // Performance tracking
            $table->integer('orders_processed')->default(0);
            $table->integer('total_tips')->default(0);
            $table->float('rating')->nullable(); // Average customer rating
            $table->timestamp('last_login_at')->nullable();

            // Emergency contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();

            $table->rememberToken();
            $table->timestamps();

            // Indexes
            $table->index('position');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
