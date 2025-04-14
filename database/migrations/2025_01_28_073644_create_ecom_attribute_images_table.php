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
        Schema::create('ecom_attribute_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ecommerce_attribute_id');
            $table->foreign('ecommerce_attribute_id')->references('id')->on('ecommerce_attributes')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecom_attribute_images');
    }
};
