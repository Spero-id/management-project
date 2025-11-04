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
        Schema::table('prospects', function (Blueprint $table) {
            // Add new separate year fields
            $table->string('target_from_year')->after('target_to_month');
            $table->string('target_to_year')->after('target_from_year');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $table) {
            // Remove the new year fields
            $table->dropColumn(['target_from_year', 'target_to_year']);
        });
    }
};
