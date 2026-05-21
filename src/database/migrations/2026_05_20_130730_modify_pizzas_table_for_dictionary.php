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
        Schema::table('pizzas', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->text('description')->nullable()->after('name');
            $table->unsignedBigInteger('base_ingredient_id')->nullable()->after('description');
            $table->unsignedBigInteger('sauce_ingredient_id')->nullable()->after('base_ingredient_id');
            $table->enum('spiciness', ['not_spicy', 'moderately_spicy', 'spicy', 'very_spicy'])->default('not_spicy')->after('sauce_ingredient_id');
            $table->boolean('is_vegan')->default(false)->after('spiciness');
            $table->decimal('price', 8, 2)->nullable()->after('is_vegan');
            $table->boolean('is_active')->default(true)->after('price');

            $table->foreign('base_ingredient_id')->references('id')->on('ingredients')->onDelete('set null');
            $table->foreign('sauce_ingredient_id')->references('id')->on('ingredients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pizzas', function (Blueprint $table) {
            $table->dropForeign(['base_ingredient_id']);
            $table->dropForeign(['sauce_ingredient_id']);
            $table->dropColumn(['name', 'description', 'base_ingredient_id', 'sauce_ingredient_id', 'spiciness', 'is_vegan', 'price', 'is_active']);
        });
    }
};
