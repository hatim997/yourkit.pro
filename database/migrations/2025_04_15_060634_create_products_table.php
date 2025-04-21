<?php

use App\Models\Category;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('productId')->nullable();
            $table->longText('description')->nullable();
            $table->foreignIdFor(Category::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(SubCategory::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('slug');
            $table->double('mrp')->default(0);
            $table->double('price')->nullable();
            $table->unsignedBigInteger('no_of_stock')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('product_type')->default(1); // 1 = Bundle, 2 = Ecommerce
            $table->text('size_chart')->nullable();
            $table->timestamps();
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
