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
        Schema::create('watches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            
            // Features and box contents (stored as JSON or plain text)
            $table->json('features')->nullable(); // array of bullet points
            $table->json('box_contents')->nullable(); // array of items in box
            
            $table->decimal('price', 10, 2);
            $table->decimal('sale', 10, 2)->nullable();
             
            $table->string('image')->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('image4')->nullable();
            $table->string('image5')->nullable();
            $table->string('image6')->nullable();
            
            $table->string('color')->nullable();
            $table->string('color1')->nullable();
            $table->string('color2')->nullable();
            $table->string('color3')->nullable();
            $table->string('color4')->nullable();
            $table->string('color5')->nullable();
            $table->string('color6')->nullable();
            
            $table->boolean('is_available')->default(true);
            $table->boolean('featured')->default(false);
            
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watches');
    }
};