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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->index()->constrained('carts')->cascadeOnDelete();
            $table->foreignId('product_id')->index()->constrained('products')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('price',15,2)->default(0);
            $table->decimal('total',15,2)->default(0);
            $table->string('options_hash', 64)->default('');
            $table->timestamps();
            $table->unique(['cart_id', 'product_id', 'options_hash']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
