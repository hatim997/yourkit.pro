<?php

use App\Models\Order;
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
        Schema::create('logo_displays', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(User::class)->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('image')->nullable();
            $table->text('comment')->nullable();
            $table->tinyInteger('approval_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logo_displays');
    }
};
