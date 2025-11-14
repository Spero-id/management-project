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
        Schema::create('prospects', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('no_handphone');
            $table->string('email');
            $table->string('company');
            $table->string('company_identity');
            $table->string('target_from_month');
            $table->string('target_to_month');
            $table->foreignId('status_id')->constrained('prospect_status')->onDelete('cascade');
            $table->text('note')->nullable();
            $table->foreignId('pre_sales')->constrained('users')->onDelete('cascade');
            $table->string('document')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            // buatkan untuk menandakan apakah prospect sudah diubah menjadi project
            $table->boolean('is_converted_to_project')->default(false);

            // $table->boolean('is_')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospects');
    }
};
