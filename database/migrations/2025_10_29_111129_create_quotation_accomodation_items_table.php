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
        Schema::create('quotation_accommodation_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('quotation_id')->constrained()->onDelete('cascade');
            $table->bigInteger('unit_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_accommodation_items');
    }
};
