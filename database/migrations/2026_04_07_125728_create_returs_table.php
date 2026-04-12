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
        Schema::create('returs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualans_id')->constrained('penjualans')->onDelete('cascade'); //menyambungkan retur dengan penjualan
            $table->date('tanggal_retur');
            $table->decimal('total_retur', 15, 2);
            $table->text('alasan_retur')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returs');
    }
};
