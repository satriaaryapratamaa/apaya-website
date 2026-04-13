<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjualanController;

use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/index', [PenjualanController::class, 'index'])->name('penjualan.index');
// Route::get('/tambahJualan', [PenjualanController::class, 'create'])->name('penjualan.tambahPenjualan');
// Route::post('/tambahJualan', [PenjualanController::class, 'store'])->name('penjualan.tambahPenjualan');
Route::resource('penjualan', PenjualanController::class);

use App\Http\Controllers\ReturController;
Route::resource('retur', ReturController::class);

Route::get('/api/penjualan/{id}', function($id) {
    return App\Models\DetailPenjualan::join('produks', 'detail_penjualans.produks_id', '=', 'produks.id')
        ->where('penjualans_id', $id)
        ->select('detail_penjualans.*', 'produks.nama_produk', 'produks.harga_barang')
        ->get();
});

use App\Http\Controllers\LaporanController;
Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
Route::get('/laporan/retur', [LaporanController::class, 'retur'])->name('laporan.retur');

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/login', function () {
    return view('auth.login');
});
