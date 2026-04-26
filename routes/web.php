<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReturController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/admin/pages/index', [PenjualanController::class, 'index'])->name('penjualan.index');
Route::get('/admin/pages/tambahJualan', [PenjualanController::class, 'create'])->name('penjualan.tambahPenjualan');
Route::post('/tambahJualan', [PenjualanController::class, 'store'])->name('penjualan.tambahPenjualan');
Route::resource('/admin/pages/penjualan', PenjualanController::class);


Route::resource('retur', ReturController::class);

Route::get('/api/penjualan/{id}', function($id) {
    return App\Models\DetailPenjualan::join('produks', 'detail_penjualans.produks_id', '=', 'produks.id')
        ->where('penjualans_id', $id)
        ->select('detail_penjualans.*', 'produks.nama_produk', 'produks.harga_barang')
        ->get();
});


Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
Route::get('/laporan/retur', [LaporanController::class, 'retur'])->name('laporan.retur');

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/login', function () {
    return view('auth.login');
});
