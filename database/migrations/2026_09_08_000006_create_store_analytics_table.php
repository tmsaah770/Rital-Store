<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_analytics', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('visitors_count')->default(0);
            $table->integer('whatsapp_clicks')->default(0);
            $table->integer('product_clicks')->default(0);
            $table->integer('sales_count')->default(0);
            $table->integer('returns_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_analytics');
    }
};
