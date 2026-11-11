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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->enum('status',[
                'pending',
                'completed',
                'shipped',
                'cancelled',
                'delivered',
                'refunded',
            ])->default('pending');
            $table->decimal('sub_total',15,2)->default(0);
            $table->decimal('total',15,2)->default(0);
            $table->enum('payment_type',['visa','cod','wallet'])->default('cod');
            $table->text('notes')->nullable();
            $table->decimal('discount',15,2)->default(0);
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->string('conversion_image')->nullable();
            $table->text('address')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
