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
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('target_gross_profit')->default(0);
            $table->unsignedBigInteger('target_monthly')->default(0);
            $table->unsignedBigInteger('target_yearly')->default(0);
            $table->year('year');
            $table->string('status')->default('active');
            $table->timestamps();

            $table->unique(['user_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_targets');
    }
};
