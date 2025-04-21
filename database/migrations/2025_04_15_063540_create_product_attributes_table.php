<?php

use App\Models\Attribute;
use App\Models\Product;
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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Attribute::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->tinyInteger('status')->default(1);
            $table->string('value');
            $table->double('ecom_cost', 10, 2)->default(0.00);
            $table->double('extra_cost', 10, 2)->default(0.00);
            $table->string('image', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
