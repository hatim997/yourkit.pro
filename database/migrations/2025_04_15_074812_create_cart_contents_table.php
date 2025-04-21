<?php

use App\Models\Cart;
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
        Schema::create('cart_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cart::class)->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->longText('contents')->nullable(); // contents (longtext, nullable)
            $table->longText('positions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_contents');
    }
};
