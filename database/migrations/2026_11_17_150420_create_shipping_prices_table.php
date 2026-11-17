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
        Schema::create('shipping_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
//            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->decimal('price',15,2)->default(0);
            $table->timestamps();
            $table->unique(['shipping_company_id', 'city_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_prices');
    }
};
