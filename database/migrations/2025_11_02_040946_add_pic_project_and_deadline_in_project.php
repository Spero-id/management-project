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
        // The projects table is named 'projects' (plural) in the model
        Schema::table('projects', function (Blueprint $table) {
            $table->string('pic_project')->nullable();
            $table->integer('execution_time')->comment("in days")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the added columns on the projects table
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'pic_project')) {
                $table->dropColumn('pic_project');
            }

            if (Schema::hasColumn('projects', 'execution_time')) {
                $table->dropColumn('execution_time');
            }
        });
    }
};
