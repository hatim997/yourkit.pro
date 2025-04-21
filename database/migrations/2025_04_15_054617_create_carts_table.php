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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('cartId');
            $table->string('sessionId');
            $table->string('table');
            $table->string('table_id');
            $table->double('size_extra_cost')->default(0.00);
            $table->double('position_extra_cost')->default(0.00);
            $table->double('info_extra_cost')->default(0.00);
            $table->double('total_extra_cost')->default(0.00);
            $table->double('total_cost')->default(0.00);
            $table->string('logo')->nullable();
            $table->text('comment')->nullable();
            $table->enum('is_email_checked', [1, 0])->default(0);
            $table->enum('is_phone_checked', [1, 0])->default(0);
            $table->enum('is_phone_on_t-shirt', ['1', '0'])->default('0');
            $table->enum('is_phone_on_hoodie', ['1', '0'])->default('0');
            $table->enum('is_email_on_t-shirt', ['1', '0'])->default('0');
            $table->enum('is_email_on_hoodie', ['1', '0'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
