<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjualanController;

Route::get('/', function () {
    return view('penjualan.dashboard');
});

Route::get('/index', [PenjualanController::class, 'index'])->name('penjualan.index');
// Route::get('/tambahJualan', [PenjualanController::class, 'create'])->name('penjualan.tambahPenjualan');
// Route::post('/tambahJualan', [PenjualanController::class, 'store'])->name('penjualan.tambahPenjualan');
Route::resource('penjualan', PenjualanController::class);

Route::get('/admin', function () {
    return view('admin.dashboard');
});

Route::get('/login', function () {
    return view('auth.login');
});
