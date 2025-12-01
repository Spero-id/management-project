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
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('accommodation_wilayah')->nullable();
            $table->bigInteger('accommodation_hotel_rooms')->nullable();
            $table->bigInteger('accommodation_people')->nullable();
            $table->bigInteger('accommodation_target_days')->nullable();
            $table->bigInteger('accommodation_plane_ticket_price')->nullable();


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
