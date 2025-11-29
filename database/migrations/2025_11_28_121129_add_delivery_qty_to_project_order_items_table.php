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
            $table->integer('delivery_qty')->default(0)->after('stock_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_order_items', function (Blueprint $table) {
            $table->dropColumn('delivery_qty');
        });
    }
};
