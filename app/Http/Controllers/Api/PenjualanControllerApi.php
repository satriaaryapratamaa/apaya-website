<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Retur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenjualanControllerApi extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penjualans = Penjualan::with('produk')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data penjualan berhasil diambil',
            'data'    => $penjualans
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'tanggal_penjualan'           => 'required|date',
            'items'                       => 'required|array',
            'items.*.produk_id'           => 'required|exists:produks,id',
            'items.*.stok_saat_ini_fisik' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $createdPenjualan = [];

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

                    $penjualan = Penjualan::create([
                        'produk_id'         => $produk->id,
                        'tanggal_penjualan' => $request->tanggal_penjualan,
                        'jumlah_terjual'    => $jumlah_terjual,
                        'harga_jual'        => $produk->harga_jual,
                        'total_omzet'       => $total_omzet,
                        'total_modal'       => $total_modal,
                        'total_keuntungan'  => $total_keuntungan,
                    ]);

                    $createdPenjualan[] = $penjualan;
                }

                $produk->update(['stok_saat_ini' => $stok_saat_ini_fisik]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Laporan penjualan berhasil disimpan melalui API',
                'data'    => $createdPenjualan
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
                'errors'  => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $penjualan = Penjualan::with('produk')->find($id);

        if (!$penjualan) {
            return response()->json([
                'success' => false,
                'message' => 'Data penjualan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data penjualan berhasil diambil',
            'data'    => $penjualan
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $penjualan = Penjualan::find($id);

        if (!$penjualan) {
            return response()->json([
                'success' => false,
                'message' => 'Data penjualan tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tanggal_penjualan' => 'required|date',
            'jumlah_terjual'    => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Catatan: Jika update manual ini membutuhkan kalkulasi ulang stok, omzet, modal,
        // dan keuntungan seperti di 'store', silakan sesuaikan rumusnya di sini.
        $penjualan->update([
            'tanggal_penjualan' => $request->tanggal_penjualan,
            'jumlah_terjual'    => $request->jumlah_terjual
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data penjualan berhasil diperbarui',
            'data'    => $penjualan
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);

        if (!$penjualan) {
            return response()->json([
                'success' => false,
                'message' => 'Data penjualan tidak ditemukan'
            ], 404);
        }

        $penjualan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data penjualan berhasil dihapus'
        ], 200);
    }
}
