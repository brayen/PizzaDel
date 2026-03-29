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
        Schema::create('client_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            
            // Personal information
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            
            // Food preferences (JSON)
            $table->json('pizza_preferences')->nullable(); // favorite ingredients, allergies
            $table->json('dietary_restrictions')->nullable(); // vegetarian, gluten-free, etc.
            
            // Notification settings
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('promotional_emails')->default(true);
            
            // Loyalty program
            $table->integer('loyalty_points')->default(0);
            $table->enum('discount_level', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze');
            $table->decimal('total_spent', 10, 2)->default(0);
            
            // Order statistics
            $table->integer('total_orders')->default(0);
            $table->timestamp('last_order_at')->nullable();
            
            // Preferences
            $table->string('preferred_payment_method')->nullable(); // card, cash, online
            $table->integer('preferred_delivery_time')->nullable(); // minutes
            
            $table->timestamps();
            
            // Indexes
            $table->index('client_id');
            $table->index('discount_level');
            $table->index('loyalty_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_preferences');
    }
};
