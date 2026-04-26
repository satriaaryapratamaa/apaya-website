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
        Schema::create('stok_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->integer('stok_awal'); // Stok di awal periode/hari
            $table->integer('stok_masuk'); // Total dari tabel pembelian di hari itu
            $table->integer('stok_sisa'); // Hasil hitung fisik (input manual)
            $table->integer('jumlah_terjual'); // Hasil: (Awal + Masuk) - Sisa
            $table->decimal('total_omzet', 15, 2); // Hasil: jumlah_terjual * harga_jual
            $table->date('tanggal_opname');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_opnames');
    }
};
