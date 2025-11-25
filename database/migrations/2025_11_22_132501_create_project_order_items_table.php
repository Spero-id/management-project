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
        Schema::create('project_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_order_id')->constrained('project_orders')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('quotation_item_id')->constrained('quotation_items')->onDelete('cascade');
            $table->integer('required_qty')->default(0);
            $table->integer('stock_used')->default(0);
            $table->date('estimated_arrival_date')->nullable();
            $table->string('order_status')->default('pending'); // pending, partial, complete
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_order_items');
    }
};
