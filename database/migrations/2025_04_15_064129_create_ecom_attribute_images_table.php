<?php

use App\Models\EcommerceAttribute;
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
            $table->foreignIdFor(EcommerceAttribute::class)
                ->constrained()
                ->cascadeOnDelete();
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
