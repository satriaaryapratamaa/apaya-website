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
            $table->string('nomor_invoice')->unique();
            $table->date('tanggal_penjualan');
            $table->decimal('total_bayar', 15, 2);
            $table->string('customer_name')->nullable();
            $table->enum('status', ['lunas', 'hutang', 'dibatalkan'])->default('lunas');
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
