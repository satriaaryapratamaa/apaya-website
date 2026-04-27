<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::latest()->get();
        return view('produk.index', compact('produks'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:produks,sku',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok_saat_ini' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            Produk::create([
                'nama_produk' => $request->nama_produk,
                'sku' => $request->sku,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'stok_saat_ini' => $request->stok_saat_ini,
            ]);

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Barang baru berhasil ditambahkan ke dalam Master Data!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan barang: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:produks,sku,' . $produk->id,
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok_saat_ini' => 'required|integer|min:0',
        ]);

        try {
            $produk->update([
                'nama_produk' => $request->nama_produk,
                'sku' => $request->sku,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'stok_saat_ini' => $request->stok_saat_ini,
            ]);

            return redirect()->route('produk.index')->with('success', 'Data Master Barang berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui barang: ' . $e->getMessage())->withInput();
        }
    }
}
