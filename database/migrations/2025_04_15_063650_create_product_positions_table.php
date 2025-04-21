<?php

use App\Models\SubCategory;
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
        Schema::create('product_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SubCategory::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('title', 255);                    // Title (required)
            $table->string('type', 255)->nullable();         // Type (optional)
            $table->tinyInteger('status')->default(1);       // Status (default 1)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_positions');
    }
};
