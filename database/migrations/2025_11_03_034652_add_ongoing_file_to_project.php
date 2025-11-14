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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('drawing_file')->nullable();
            $table->string('wbs_file')->nullable()->after('drawing_file');
            $table->string('project_schedule_file')->nullable()->after('wbs_file');
            $table->string('purchase_schedule_file')->nullable()->after('project_schedule_file');
            $table->string('pengajuan_material_project_file')->nullable()->after('purchase_schedule_file');
            $table->string('pengajuan_tools_project_file')->nullable()->after('pengajuan_material_project_file');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            //
        });
    }
};
