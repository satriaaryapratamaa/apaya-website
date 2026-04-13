<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Retur;
use App\Models\DetailPenjualan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Total Penjualan & Retur Bulan Ini
        $totalJual = Penjualan::whereMonth('tanggal_penjualan', $currentMonth)
                                ->whereYear('tanggal_penjualan', $currentYear)
                                ->where('status', 'lunas')
                                ->sum('total_bayar');

        $totalRetur = Retur::whereMonth('tanggal_retur', $currentMonth)
                                ->whereYear('tanggal_retur', $currentYear)
                                ->sum('total_retur');

        // Data Penjualan Bulan Ini (contoh sederhana 3 bulan terakhir hingga bulan ini untuk bar chart)
        $labelBulan = [];
        $dataPenjualan = [];
        for ($i = 2; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $labelBulan[] = $monthDate->translatedFormat('F');
            $sumSales = Penjualan::whereMonth('tanggal_penjualan', $monthDate->month)
                                 ->whereYear('tanggal_penjualan', $monthDate->year)
                                 ->where('status', 'lunas')
                                 ->sum('total_bayar');
            $dataPenjualan[] = $sumSales;
        }

        // Grafik Produk Terlaris (Top 4 products sold this month)
        $topProducts = DetailPenjualan::join('penjualans', 'detail_penjualans.penjualans_id', '=', 'penjualans.id')
                            ->join('produks', 'detail_penjualans.produks_id', '=', 'produks.id')
                            ->whereMonth('penjualans.tanggal_penjualan', $currentMonth)
                            ->whereYear('penjualans.tanggal_penjualan', $currentYear)
                            ->where('penjualans.status', 'lunas')
                            ->select('produks.nama_produk', DB::raw('SUM(detail_penjualans.jumlah) as total_qty'))
                            ->groupBy('produks.nama_produk', 'produks.id')
                            ->orderBy('total_qty', 'DESC')
                            ->take(4)
                            ->get();

        $labelProduk = $topProducts->pluck('nama_produk')->toArray();
        $dataProduk = $topProducts->pluck('total_qty')->toArray();

        // If empty data, provide default for visual purposes so chart doesn't break
        if(empty($labelProduk)){
            $labelProduk = ['Belum ada data'];
            $dataProduk = [0];
        }

        return view('penjualan.dashboard', compact('totalJual', 'totalRetur', 'labelBulan', 'dataPenjualan', 'labelProduk', 'dataProduk'));
    }
}
