<?php

use App\Models\ProductPosition;
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
            $table->foreignIdFor(ProductPosition::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('title');
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(1);
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
