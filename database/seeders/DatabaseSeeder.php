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
        
        
        // Add Products (Adjusted for main schema)
        $produkId1 = DB::table('produks')->insertGetId([
            'nama_produk' => 'Biji Kopi Arabica 1Kg',
            'sku' => 'KOP-ARB-001-' . rand(10,99),
            'harga_beli' => 100000,
            'harga_jual' => 150000,
            'stok_saat_ini' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $produkId2 = DB::table('produks')->insertGetId([
            'nama_produk' => 'Biji Kopi Robusta 1Kg',
            'sku' => 'KOP-RBS-002-' . rand(10,99),
            'harga_beli' => 80000,
            'harga_jual' => 120000,
            'stok_saat_ini' => 45,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $produkId3 = DB::table('produks')->insertGetId([
            'nama_produk' => 'Sirup Karamel 500ml',
            'sku' => 'SRP-KRM-001-' . rand(10,99),
            'harga_beli' => 60000,
            'harga_jual' => 85000,
            'stok_saat_ini' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add Penjualan (Adjusted for main schema)
        DB::table('penjualans')->insert([
            [
                'produk_id' => $produkId1,
                'tanggal_penjualan' => Carbon::now()->subDays(5),
                'jumlah_terjual' => 2,
                'harga_jual' => 150000,
                'total_omzet' => 300000,
                'total_modal' => 200000,
                'total_keuntungan' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'produk_id' => $produkId2,
                'tanggal_penjualan' => Carbon::now()->subDays(1),
                'jumlah_terjual' => 1,
                'harga_jual' => 120000,
                'total_omzet' => 120000,
                'total_modal' => 80000,
                'total_keuntungan' => 40000,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
