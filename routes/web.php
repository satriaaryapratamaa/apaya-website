<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\ProdukController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
// Route::resource('produk', ProdukController::class);

// Route::get('/admin/pages/index', [PenjualanController::class, 'index'])->name('penjualan.index');
// Route::get('/admin/pages/tambahJualan', [PenjualanController::class, 'create'])->name('penjualan.tambahPenjualan');
// Route::get('/admin/pages/editJualan{id}', [PenjualanController::class, 'edit'])->name('penjualan.edit');
// Route::post('/tambahJualan', [PenjualanController::class, 'store'])->name('penjualan.tambahPenjualan');
Route::prefix('admin/pages')->group(function () {

    Route::resource('penjualan', PenjualanController::class)->names([
        'index'  => 'penjualan.index',
        'create' => 'penjualan.create',
        'store'  => 'penjualan.store',
        'edit'   => 'penjualan.edit',
        'update' => 'penjualan.update',
    ]);

    Route::resource('produk', ProdukController::class)->names([
        'index'  => 'produk.index',
        'create' => 'produk.create',
        'store'  => 'produk.store',
        'edit'   => 'produk.edit',
        'update' => 'produk.update',
    ]);

    Route::resource('retur', ReturController::class)->names([
        'index' => 'retur.index',
        'create' => 'retur.create',
        'store' => 'retur.store',
    ]);
});


Route::get('/laporan/penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan');
Route::get('/laporan/retur', [LaporanController::class, 'retur'])->name('laporan.retur');

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/login', function () {
    return view('auth.login');
});
