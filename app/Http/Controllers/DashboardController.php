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

        $lastMonth = Carbon::now()->subMonth()->month;
        $lastMonthYear = Carbon::now()->subMonth()->year;

        // --- 1. Total Penjualan & Keuntungan (Bulan Ini) ---
        $statsCurrent = Penjualan::whereMonth('tanggal_penjualan', $currentMonth)
                            ->whereYear('tanggal_penjualan', $currentYear)
                            ->select(
                                DB::raw('SUM(total_omzet) as total_jual'),
                                DB::raw('SUM(total_keuntungan) as total_untung')
                            )->first();

        $totalJual = $statsCurrent->total_jual ?? 0;
        $totalUntung = $statsCurrent->total_untung ?? 0;

        // --- 1b. Total Penjualan & Keuntungan (Bulan Kemarin untuk Persentase) ---
        $statsLast = Penjualan::whereMonth('tanggal_penjualan', $lastMonth)
                            ->whereYear('tanggal_penjualan', $lastMonthYear)
                            ->select(
                                DB::raw('SUM(total_omzet) as total_jual'),
                                DB::raw('SUM(total_keuntungan) as total_untung')
                            )->first();
        
        $lastTotalJual = $statsLast->total_jual ?? 0;
        $lastTotalUntung = $statsLast->total_untung ?? 0;

        $percentJual = $lastTotalJual > 0 ? (($totalJual - $lastTotalJual) / $lastTotalJual) * 100 : ($totalJual > 0 ? 100 : 0);
        $percentUntung = $lastTotalUntung > 0 ? (($totalUntung - $lastTotalUntung) / $lastTotalUntung) * 100 : ($totalUntung > 0 ? 100 : 0);

        // --- 2. Total Pembelian (Bulan Ini & Bulan Lalu) ---
        $totalBeli = \App\Models\Pembelian::whereMonth('tanggal_pembelian', $currentMonth)
                            ->whereYear('tanggal_pembelian', $currentYear)
                            ->sum(DB::raw('jumlah_masuk * harga_beli_satuan'));
        
        $lastTotalBeli = \App\Models\Pembelian::whereMonth('tanggal_pembelian', $lastMonth)
                            ->whereYear('tanggal_pembelian', $lastMonthYear)
                            ->sum(DB::raw('jumlah_masuk * harga_beli_satuan'));

        $percentBeli = $lastTotalBeli > 0 ? (($totalBeli - $lastTotalBeli) / $lastTotalBeli) * 100 : ($totalBeli > 0 ? 100 : 0);

        // --- 3. Data Grafik Penjualan (12 Bulan ke belakang) ---
        $labelBulan = [];
        $dataPenjualan = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $labelBulan[] = $monthDate->translatedFormat('M'); // Jan, Feb, Mar

            $sumSales = Penjualan::whereMonth('tanggal_penjualan', $monthDate->month)
                                 ->whereYear('tanggal_penjualan', $monthDate->year)
                                 ->sum('total_omzet');
            $dataPenjualan[] = $sumSales;
        }

        // --- 4. Produk Terlaris ---
        $topProducts = Penjualan::with('produk')
                            ->whereMonth('tanggal_penjualan', $currentMonth)
                            ->whereYear('tanggal_penjualan', $currentYear)
                            ->select('produk_id', DB::raw('SUM(jumlah_terjual) as total_qty'))
                            ->groupBy('produk_id')
                            ->orderBy('total_qty', 'DESC')
                            ->take(5)
                            ->get();

        // --- 5. Riwayat Transaksi Terakhir ---
        $recentTransactions = Penjualan::latest('tanggal_penjualan')->latest('id')->take(4)->get();

        // --- 6. Histori Perubahan Stok (Gabungan Pembelian dan Penjualan) ---
        $recentPembelian = \App\Models\Pembelian::with('produk')->latest('tanggal_pembelian')->latest('id')->take(5)->get()->map(function($item) {
            return [
                'waktu' => Carbon::parse($item->tanggal_pembelian)->format('d M Y - 10:00'),
                'produk' => $item->produk->nama_produk ?? 'Unknown',
                'perubahan' => '+' . $item->jumlah_masuk,
                'tipe' => 'Pembelian',
                'sort_date' => $item->tanggal_pembelian . ' 10:00:00'
            ];
        });

        $recentPenjualan = Penjualan::with('produk')->latest('tanggal_penjualan')->latest('id')->take(5)->get()->map(function($item) {
            return [
                'waktu' => Carbon::parse($item->tanggal_penjualan)->format('d M Y - 14:00'),
                'produk' => $item->produk->nama_produk ?? 'Unknown',
                'perubahan' => '-' . $item->jumlah_terjual,
                'tipe' => 'Penjualan',
                'sort_date' => $item->tanggal_penjualan . ' 14:00:00'
            ];
        });

        // Merge and sort desc by mock sort_date
        $stockMovements = collect($recentPembelian)->merge($recentPenjualan)
                            ->sortByDesc('sort_date')
                            ->take(4)
                            ->values()
                            ->all();

        return view('penjualan.dashboard', compact(
            'totalJual', 'percentJual',
            'totalBeli', 'percentBeli',
            'totalUntung', 'percentUntung',
            'labelBulan', 'dataPenjualan',
            'topProducts',
            'recentTransactions',
            'stockMovements'
        ));
    }
}
