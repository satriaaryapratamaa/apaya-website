<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with('produk')->latest()->get();
        return view('pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $produks = Produk::all();
        return view('pembelian.create', compact('produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah_masuk' => 'required|integer|min:1',
            'harga_beli_satuan' => 'required|numeric',
            'tanggal_pembelian' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Simpan data pembelian
            Pembelian::create($request->all());

            // Update stok di tabel produk
            $produk = Produk::findOrFail($request->produk_id);
            $produk->increment('stok', $request->jumlah_masuk);

            // Opsional: Update harga_beli di tabel produk jika harga supplier berubah
            $produk->update(['harga_beli' => $request->harga_beli_satuan]);

            DB::commit();
            return redirect()->route('pembelian.index')->with('success', 'Stok berhasil ditambah!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
