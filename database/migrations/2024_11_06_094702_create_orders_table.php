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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('orderID')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->double('amount', 10, 2)->default(0.00);
            $table->unsignedBigInteger('tax_percentage')->default(1);
            $table->double('taxable_amount', 10, 2)->default(0.00);
            $table->unsignedBigInteger('discount_percentage')->default(1);
            $table->double('discount_amount', 10, 2)->default(0.00);
            $table->double('final_amount', 10, 2)->default(0.00);
            $table->enum('payment_method', ['stripe', 'paypal'])->default('stripe');
            $table->unsignedBigInteger('payment_status')->default(1); // 1 => pending, 2 => Complete, 3 => Cancel
            $table->string('transaction_id')->nullable();
            $table->string('billing_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_mobile')->nullable();
            $table->string('address')->nullable();
            $table->string('town')->nullable();
            $table->string('postcode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
