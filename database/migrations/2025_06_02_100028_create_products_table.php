<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('sub_category_id')->nullable();

$table->foreign('sub_category_id')
    ->references('id')
    ->on('subcategories')
    ->onDelete('set null');

            
            $table->string('name');
            $table->text('description')->nullable();
            
            // Features and box contents (stored as JSON or plain text)
            $table->json('features')->nullable(); // array of bullet points
            $table->json('box_contents')->nullable(); // array of items in box
            
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->timestampsTz();
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