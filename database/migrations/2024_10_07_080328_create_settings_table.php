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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_name')->nuallbale();
            $table->string('display_name')->nuallbale();
            $table->string('value')->nuallbale();
            $table->boolean('is_visible')->nuallbale();
            $table->string('input_type')->nuallbale();
            $table->string('group')->nuallbale();
            $table->longText('option')->nuallbale();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
