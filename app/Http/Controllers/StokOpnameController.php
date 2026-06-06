<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokOpnameController extends Controller
{
    public function index()
    {
        // Form untuk input sisa stok harian
        $produks = Produk::all();
        return view('stok_opname.index', compact('produks'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'items' => 'required|array',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.stok_sisa' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['produk_id']);

                $stok_awal = $produk->stok; 
                $stok_sisa = $item['stok_sisa']; 

                
                $terjual = $stok_awal - $stok_sisa;

                if ($terjual > 0) {
                    $omzet = $terjual * $produk->harga_jual;
                    $modal = $terjual * $produk->harga_beli;

                    Penjualan::create([
                        'produk_id' => $produk->id,
                        'tanggal_penjualan' => $request->tanggal,
                        'jumlah_terjual' => $terjual,
                        'harga_jual' => $produk->harga_jual,
                        'total_omzet' => $omzet,
                        'total_modal' => $modal,
                        'total_keuntungan' => $omzet - $modal,
                    ]);
                }

                $produk->update(['stok' => $stok_sisa]);
            }

            DB::commit();
            return redirect()->route('penjualan.index')->with('success', 'Opname berhasil, Omzet otomatis terhitung!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal hitung opname: ' . $e->getMessage());
        }
    }
}
