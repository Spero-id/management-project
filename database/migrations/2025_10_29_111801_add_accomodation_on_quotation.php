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
            $table->integer('accommodation_hotel_rooms')->nullable();
            $table->integer('accommodation_people')->nullable();
            $table->integer('accommodation_target_days')->nullable();
            $table->integer('accommodation_plane_ticket_price')->nullable();


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
