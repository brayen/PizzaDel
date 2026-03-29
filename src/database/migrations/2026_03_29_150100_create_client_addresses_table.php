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
        Schema::create('client_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            
            // Address fields
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('postal_code');
            $table->string('country')->default('Italy');
            
            // Delivery preferences
            $table->boolean('is_default')->default(false);
            $table->text('delivery_instructions')->nullable();
            $table->string('contact_phone')->nullable();
            
            // Metadata
            $table->string('address_type')->default('home'); // home, work, other
            $table->timestamps();
            
            // Indexes
            $table->index(['client_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_addresses');
    }
};
