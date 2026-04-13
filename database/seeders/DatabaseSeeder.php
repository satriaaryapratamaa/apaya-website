<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan tidak dobel, kita truncate dulu (Opsional, tapi membatasi duplikasi saat refresh)
        // Note: pastikan tidak ada foregin key constraint errors saat truncate.
        
        // Add Products
        $produkId1 = DB::table('produks')->insertGetId([
            'nama_produk' => 'Biji Kopi Arabica 1Kg',
            'sku' => 'KOP-ARB-001-' . rand(10,99),
            'harga_barang' => 150000,
            'stok' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $produkId2 = DB::table('produks')->insertGetId([
            'nama_produk' => 'Biji Kopi Robusta 1Kg',
            'sku' => 'KOP-RBS-002-' . rand(10,99),
            'harga_barang' => 120000,
            'stok' => 45,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $produkId3 = DB::table('produks')->insertGetId([
            'nama_produk' => 'Sirup Karamel 500ml',
            'sku' => 'SRP-KRM-001-' . rand(10,99),
            'harga_barang' => 85000,
            'stok' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add Penjualan
        $penjualanId1 = DB::table('penjualans')->insertGetId([
            'nomor_invoice' => 'INV-' . strtoupper(Str::random(6)),
            'tanggal_penjualan' => Carbon::now()->subDays(5),
            'total_bayar' => 270000,
            'customer_name' => 'Budi Santoso',
            'status' => 'lunas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add Detail Penjualan 1
        DB::table('detail_penjualans')->insert([
            [
                'penjualans_id' => $penjualanId1,
                'produks_id' => $produkId1,
                'jumlah' => 1,
                'harga_satuan' => 150000,
                'subtotal' => 150000,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'penjualans_id' => $penjualanId1,
                'produks_id' => $produkId2,
                'jumlah' => 1,
                'harga_satuan' => 120000,
                'subtotal' => 120000,
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ]
        ]);
        
        $penjualanId2 = DB::table('penjualans')->insertGetId([
            'nomor_invoice' => 'INV-' . strtoupper(Str::random(6)),
            'tanggal_penjualan' => Carbon::now()->subDays(1),
            'total_bayar' => 300000,
            'customer_name' => 'Cafe Senja Nusantara',
            'status' => 'lunas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add Detail Penjualan 2
        DB::table('detail_penjualans')->insert([
            'penjualans_id' => $penjualanId2,
            'produks_id' => $produkId1,
            'jumlah' => 2,
            'harga_satuan' => 150000,
            'subtotal' => 300000,
            'created_at' => Carbon::now()->subDays(1),
            'updated_at' => Carbon::now()->subDays(1),
        ]);
        
        $penjualanId3 = DB::table('penjualans')->insertGetId([
            'nomor_invoice' => 'INV-' . strtoupper(Str::random(6)),
            'tanggal_penjualan' => Carbon::now(),
            'total_bayar' => 255000,
            'customer_name' => 'Ratna Dewi',
            'status' => 'lunas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('detail_penjualans')->insert([
            'penjualans_id' => $penjualanId3,
            'produks_id' => $produkId3,
            'jumlah' => 3,
            'harga_satuan' => 85000,
            'subtotal' => 255000,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
