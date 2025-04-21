<?php

use App\Models\AttributeValue;
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
        Schema::create('product_extra_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(SubCategory::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(AttributeValue::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('attribute_value', 200);       // Required string value
            $table->double('amount', 10, 2)->default(0.00); // Default amount is 0.00
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_extra_costs');
    }
};
