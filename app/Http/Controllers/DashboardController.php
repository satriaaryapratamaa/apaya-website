<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Retur;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Total Omzet & Untung Bersih Bulan Ini
        $stats = Penjualan::whereMonth('tanggal_penjualan', $currentMonth)
                            ->whereYear('tanggal_penjualan', $currentYear)
                            ->select(
                                DB::raw('SUM(total_omzet) as total_jual'),
                                DB::raw('SUM(total_keuntungan) as total_untung')
                            )->first();

        $totalJual = $stats->total_jual ?? 0;
        $totalUntung = $stats->total_untung ?? 0;

        // Total Retur (Sekarang tidak ada total_retur, kita hitung dari jumlah * harga di produk)
        $totalRetur = Retur::join('produks', 'returs.produk_id', '=', 'produks.id')
                            ->whereMonth('tanggal_retur', $currentMonth)
                            ->whereYear('tanggal_retur', $currentYear)
                            ->sum(DB::raw('returs.jumlah_retur * produks.harga_jual'));

        // Data Grafik Penjualan 3 Bulan Terakhir
        $labelBulan = [];
        $dataPenjualan = [];
        for ($i = 2; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $labelBulan[] = $monthDate->translatedFormat('F');

            $sumSales = Penjualan::whereMonth('tanggal_penjualan', $monthDate->month)
                                 ->whereYear('tanggal_penjualan', $monthDate->year)
                                 ->sum('total_omzet');
            $dataPenjualan[] = $sumSales;
        }

        // Grafik Produk Terlaris (Top 4)
        $topProducts = Penjualan::with('produk')
                            ->whereMonth('tanggal_penjualan', $currentMonth)
                            ->whereYear('tanggal_penjualan', $currentYear)
                            ->select('produk_id', DB::raw('SUM(jumlah_terjual) as total_qty'))
                            ->groupBy('produk_id')
                            ->orderBy('total_qty', 'DESC')
                            ->take(4)
                            ->get();

        $labelProduk = $topProducts->map(function($item) {
            return $item->produk->nama_produk;
        })->toArray();

        $dataProduk = $topProducts->pluck('total_qty')->toArray();

        if(empty($labelProduk)){
            $labelProduk = ['Belum ada data'];
            $dataProduk = [0];
        }

        return view('penjualan.dashboard', compact(
            'totalJual',
            'totalUntung',
            'totalRetur',
            'labelBulan',
            'dataPenjualan',
            'labelProduk',
            'dataProduk'
        ));
    }
}
