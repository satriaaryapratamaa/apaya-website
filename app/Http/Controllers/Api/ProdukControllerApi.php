<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProdukControllerApi extends Controller
{
    public function index()
    {
        $prods = Produk::get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar produk berhasil diambil',
            'data' => $prods,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk'   => 'required|string|max:255',
            'sku'           => 'required|string|max:50|unique:produks,sku',
            'harga_beli'    => 'required|numeric|min:0',
            'harga_jual'    => 'required|numeric|min:0',
            'stok_saat_ini' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $produk = Produk::create([
                'nama_produk'   => $request->nama_produk,
                'sku'           => $request->sku,
                'harga_beli'    => $request->harga_beli,
                'harga_jual'    => $request->harga_jual,
                'stok_saat_ini' => $request->stok_saat_ini,
            ]);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Barang baru berhasil ditambahkan ke dalam Master Data!',
                'data' => $produk
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'Gagal menambahkan barang atau server error',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json([
                'error' => true,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'error' => false,
            'message' => 'Detail produk berhasil diambil',
            'data' => $produk
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json([
                'error' => true,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_produk'   => 'required|string|max:255',
            'sku'           => 'required|string|max:50|unique:produks,sku,' . $produk->id,
            'harga_beli'    => 'required|numeric|min:0',
            'harga_jual'    => 'required|numeric|min:0',
            'stok_saat_ini' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $produk->update([
                'nama_produk'   => $request->nama_produk,
                'sku'           => $request->sku,
                'harga_beli'    => $request->harga_beli,
                'harga_jual'    => $request->harga_jual,
                'stok_saat_ini' => $request->stok_saat_ini,
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Data Master Barang berhasil diperbarui!',
                'data' => $produk
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal memperbarui barang atau server error',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return response()->json([
                'error' => true,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        try {
            $produk->delete();
            return response()->json([
                'error' => false,
                'message' => 'Data Master Barang berhasil dihapus!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal menghapus barang atau server error',
                'exception' => $e->getMessage()
            ], 500);
        }
    }

    public function history()
    {
        $history = Produk::orderBy('updated_at', 'desc')->get();

        return response()->json([
            'error' => false,
            'message' => 'Riwayat perubahan stok berhasil diambil',
            'data' => $history
        ], 200);
    }

    public function notifikasi()
    {
        $lowStockProducts = Produk::where('stok_saat_ini', '<=', 10)->get();

        $notifikasi = [];

        foreach($lowStockProducts as $produk) {
            $notifikasi[] = [
                'type' => 'peringatan_stok',
                'message' => "Stok {$produk->nama_produk} dibawah minimum! Sisa tinggal {$produk->stok_saat_ini}",
                'date' => $produk->updated_at->format('Y-m-d H:i'),
                'color' => 'orange'
            ];
        }

        $notifikasi[] = [
            'type' => 'info',
            'message' => "Sistem Master Data berjalan normal",
            'date' => date('Y-m-d H:i'),
            'color' => 'green'
        ];
        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil diambil',
            'data' => $notifikasi
        ], 200);
    }
}
