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
        Schema::table('project_order_items', function (Blueprint $table) {
            $table->string('po_number')->nullable()->after('order_status');
            $table->string('po_file_path')->nullable()->after('po_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_order_items', function (Blueprint $table) {
            $table->dropColumn(['po_number', 'po_file_path']);
        });
    }
};
