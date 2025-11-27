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
        Schema::table('products', function (Blueprint $table) {
            $table->string('brand')->nullable();
            $table->string('type')->nullable();
            $table->string('distributor_origin')->nullable();
            $table->integer('weight')->nullable();
            $table->bigInteger('shipping_fee_by_air')->nullable();
            $table->decimal("dollar_base_price", 10, 2)->nullable();
            $table->bigInteger('base_price_rupiah_for_luar_negeri')->nullable();
            $table->bigInteger('base_price_rupiah_for_jakarta')->nullable();
            $table->integer('margin_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
