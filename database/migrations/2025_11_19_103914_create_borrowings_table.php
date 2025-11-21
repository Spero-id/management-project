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
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('no_peminjaman')->unique();
            $table->enum('keperluan', ['POC', 'DEMO', 'BACKUP']);
            $table->string('penanggung_jawab');
            $table->enum('status', ['borrowed', 'returned','outstanding'])->default('borrowed');
            $table->date("tanggal_pengembalian")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
