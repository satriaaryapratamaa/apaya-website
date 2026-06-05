<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Retur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with('produk')->latest()->get();
        return view('penjualan.index', compact('penjualans'));
    }

    public function create()
    {

        $produks = Produk::all();
        return view('penjualan.tambahPenjualan', compact('produks'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'tanggal_penjualan' => 'required|date',
            'items'             => 'required|array',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.stok_saat_ini_fisik' => 'required|integer|min:0',
        ]);

        try {
            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['produk_id']);

                $stok_sebelumnya = $produk->stok_saat_ini ?? 0;

                $jumlah_pembelian = Pembelian::where('produks_id', $produk->id)
                    ->whereDate('tanggal_pembelian', $request->tanggal_penjualan)
                    ->sum('jumlah_masuk');

                $total_retur = Retur::where('produk_id', $produk->id)
                    ->whereDate('tanggal_retur', $request->tanggal_penjualan)
                    ->sum('jumlah_retur');

                $stok_saat_ini_fisik = $item['stok_saat_ini_fisik'];

                $formula = $stok_saat_ini_fisik - $jumlah_pembelian - $total_retur - $stok_sebelumnya;
                $jumlah_terjual = abs($formula);

                if ($jumlah_terjual > 0) {
                    $total_omzet = $jumlah_terjual * $produk->harga_jual;
                    $total_modal = $jumlah_terjual * $produk->harga_beli;
                    $total_keuntungan = $total_omzet - $total_modal;

                    Penjualan::create([
                        'produk_id'         => $produk->id,
                        'tanggal_penjualan' => $request->tanggal_penjualan,
                        'jumlah_terjual'    => $jumlah_terjual,
                        'harga_jual'        => $produk->harga_jual,
                        'total_omzet'       => $total_omzet,
                        'total_modal'       => $total_modal,
                        'total_keuntungan'  => $total_keuntungan,
                    ]);
                }

                $produk->update(['stok_saat_ini' => $stok_saat_ini_fisik]);
            }

            DB::commit();

            return redirect()->route('penjualan.index')->with('success', 'Laporan stok harian berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $penjualan = Penjualan::with('produk')->findOrFail($id);
        return view('penjualan.edit', compact('penjualan'));
    }

    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::findOrFail($id);

        $request->validate([
            'tanggal_penjualan' => 'required|date',
            'jumlah_terjual' => 'required|integer|min:1'
        ]);

        $penjualan->update([
            'tanggal_penjualan' => $request->tanggal_penjualan,
            'jumlah_terjual' => $request->jumlah_terjual
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil diperbarui');
    }
}
