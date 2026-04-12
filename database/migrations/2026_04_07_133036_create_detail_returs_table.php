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
        Schema::create('detail_returs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retur_id')->constrained('returs')->onDelete('cascade');
            $table->foreignId('produks_id')->constrained('produks');
            $table->integer('jumlah_retur');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_returs');
    }
};
