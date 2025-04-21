<?php

use App\Models\Bundle;
use App\Models\Order;
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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Bundle::class)->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('cart_id')->nullable();
            $table->double('amount', 10, 2)->default(0.00);
            $table->longText('attributes')->nullable();
            $table->longText('positions')->nullable();
            $table->tinyInteger('is_email_checked')->default(0);
            $table->tinyInteger('is_phone_checked')->default(0);
            $table->string('comments', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
