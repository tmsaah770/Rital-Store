<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // مثل #1024
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('customer_address');
            $table->string('customer_city');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('payment_method')->default('cod'); // الدفع عند الاستلام
            $table->enum('status', ['preparing', 'out_for_delivery', 'delivered', 'failed'])->default('preparing');
            $table->string('failure_reason')->nullable();
            $table->text('failure_details')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
