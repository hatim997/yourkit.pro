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
            $table->string('cartId')->unique();
            $table->string('sessionId')->nullable();
            $table->string('table')->nullable();
            $table->unsignedBigInteger('table_id')->nullable();
            $table->double('size_extra_cost', 10, 2)->default(0.00);
            $table->double('position_extra_cost', 10, 2)->default(0.00);
            $table->double('info_extra_cost', 10, 2)->default(0.00);
            $table->double('total_extra_cost', 10, 2)->default(0.00);
            $table->double('total_cost', 10, 2)->default(0.00);
            $table->string('logo')->nullable();
            $table->text('comment')->nullable();
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
