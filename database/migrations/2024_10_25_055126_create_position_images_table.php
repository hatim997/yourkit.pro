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
        Schema::create('position_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_position_id');
            $table->foreign('product_position_id')->references('id')->on('product_positions')->onDelete('cascade');
            $table->string('title');
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_images');
    }
};
