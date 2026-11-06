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
        Schema::create('cart_item_attribute_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_item_id')->index()->constrained('cart_items')->cascadeOnDelete();
            $table->foreignId('attribute_option_id')->index()->constrained('attribute_options')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_item_attribute_options');
    }
};
