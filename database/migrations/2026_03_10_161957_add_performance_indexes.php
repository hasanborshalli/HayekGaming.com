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
    Schema::table('products', function (Blueprint $table) {
        $table->index('is_available');
        $table->index('featured');
        $table->index('isNew');
        $table->index('category_id');
        $table->index('sub_category_id');
    });

    Schema::table('watches', function (Blueprint $table) {
        $table->index('is_available');
        $table->index('featured');
        $table->index('type_id');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};