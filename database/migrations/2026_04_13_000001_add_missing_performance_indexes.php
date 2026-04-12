<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix 3: order_items.order_id is the most-queried FK — every order detail/thankyou page hits it
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('watch_id');
        });

        // Fix 7: created_at is used in ORDER BY on every product/watch listing page
        Schema::table('products', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('watches', function (Blueprint $table) {
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['watch_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('watches', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
    }
};
