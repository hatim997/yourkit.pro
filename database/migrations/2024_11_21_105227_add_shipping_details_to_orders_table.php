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
        Schema::table('orders', function (Blueprint $table) {
          
            $table->string('shipping_name')->nullable()->after('country');;
            $table->string('shipping_company')->nullable()->after('shipping_name');
            $table->string('shipping_email')->nullable()->after('shipping_company');
            $table->string('shipping_mobile')->nullable()->after('shipping_email');
            $table->string('shipping_country')->nullable()->after('shipping_mobile');
            $table->string('shipping_address')->nullable()->after('shipping_country');
            $table->string('shipping_town')->nullable()->after('shipping_address');
            $table->string('shipping_postcode')->nullable()->after('shipping_town');
            $table->tinyInteger("shipping")->default(2)->comment("0 = pickup, 1 = different shipping address, 2= shipping billing same");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_name',
                'shipping_company',
                'shipping_email',
                'shipping_mobile',
                'shipping_country',
                'shipping_address',
                'shipping_town',
                'shipping_postcode',
            ]);
        });
    }
};
