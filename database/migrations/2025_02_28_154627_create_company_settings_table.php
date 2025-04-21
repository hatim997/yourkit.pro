<?php

use App\Models\Country;
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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->foreignIdFor(Country::class)->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('dark_logo')->nullable();
            $table->string('light_logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('contact-map')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->string('address')->nullable();
            $table->string('site_phone')->nullable();
            $table->string('site_email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->enum('payment_mode',['sandbox', 'production'])->default('sandbox');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
