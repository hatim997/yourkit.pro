<?php

use App\Models\User;
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
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->double('amount', 10, 2)->default(0.00);
            $table->unsignedBigInteger('tax_percentage')->default(1);
            $table->double('taxable_amount', 10, 2)->default(0.00);
            $table->unsignedBigInteger('discount_percentage')->default(1);
            $table->double('discount_amount', 10, 2)->default(0.00);
            $table->double('final_amount', 10, 2)->default(0.00);
            $table->enum('payment_method', ['stripe', 'paypal', 'authorize_net'])->default('authorize_net');
            $table->unsignedBigInteger('payment_status')->default(1); // 1 = pending, 2 = success, 3 = failed, 4 = hold
            $table->string('transaction_id')->nullable();
            $table->string('printable_logo', 200)->nullable();
            $table->string('billing_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('billing_mobile')->nullable();
            $table->string('country', 200)->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_company')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_mobile')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_town')->nullable();
            $table->string('shipping_postcode')->nullable();
            $table->string('address')->nullable();
            $table->string('town')->nullable();
            $table->string('pincode')->nullable();
            $table->string('logo', 200)->nullable();
            $table->tinyInteger('shipping')->default(2); // 0 = pickup, 1 = different shipping, 2 = same as billing
            $table->tinyInteger('order_status')->default(1); // 1 => 'Under Review', 2 => 'Design Approved', 3 => 'Waiting for Garments', 4 => 'Sent to Graphic Designer', 5 => 'In production'	
            $table->string('comment', 200)->nullable();
            $table->boolean('is_email_checked')->default(0);
            $table->boolean('is_phone_checked')->default(0);
            $table->string('invoice_path', 200)->nullable();
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
