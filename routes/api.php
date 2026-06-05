<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\Api\PenjualanControllerApi;
use App\Http\Controllers\Api\ProdukControllerApi;
use App\Http\Controllers\Api\ReturControllerApi;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('penjualan', PenjualanControllerApi::class)->names([
    'index'   => 'api.penjualan.index',
    'store'   => 'api.penjualan.store',
    'show'    => 'api.penjualan.show',
    'update'  => 'api.penjualan.update',
    'destroy' => 'api.penjualan.destroy',
]);

Route::apiResource('produk', ProdukControllerApi::class)->names([
    'index'   => 'api.produk.index',
    'store'   => 'api.produk.store',
    'show'    => 'api.produk.show',
    'update'  => 'api.produk.update',
    'destroy' => 'api.produk.destroy',
]);

Route::apiResource('retur', ReturControllerApi::class)->names([
    'index'   => 'api.retur.index',
    'create'  => 'api.retur.create',
    'store'   => 'api.retur.store',
    'show'    => 'api.retur.show',
    'update'  => 'api.retur.update',
    'destroy' => 'api.retur.destroy',
]);

// Route::resource('product',  ProdukController::class);
