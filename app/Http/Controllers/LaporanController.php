<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Retur;

class LaporanController extends Controller
{
    public function penjualan()
    {
        $penjualans = Penjualan::with('produk')->latest()->get();
        return view('laporan.penjualan', compact('penjualans'));
    }

    public function retur()
    {
        $returs = Retur::with('produk')->latest()->get();
        return view('laporan.retur', compact('returs'));
    }
}
