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
            $table->json('name');
//            $table->string('slug')->unique();
            $table->json('description')->nullable();
            $table->string('image')->nullable();

            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('price_after_discount', 15, 2)->nullable();

            $table->integer('available_quantity')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('sku')->unique()->nullable();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
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
