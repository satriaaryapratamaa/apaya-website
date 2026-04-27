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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->date('tanggal_penjualan');

            //dataa perhitungan
            $table->integer('jumlah_terjual');
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('total_omzet',  15, 2);

            $table->decimal('total_modal', 15, 2);
            $table->decimal('total_keuntungan', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
