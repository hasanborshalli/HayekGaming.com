<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('punch_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('punch_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('watch_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type')->default('product');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->timestampsTz();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('punch_order_items');
    }
};
