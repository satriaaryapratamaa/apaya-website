<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Retur;
use App\Models\Produk;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReturControllerApi extends Controller
{
    public function index()
    {
        $returs = Retur::with('produk')->latest()->get();

        return response()->json([
            'error' => false,
            'message' => 'Daftar retur berhasil diambil',
            'data' => $returs
        ], 200);
    }

    public function create()
    {
        $produks = Produk::all();
        return view('retur.create', compact('produks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'produk_id' => 'required|exists:produks,id',
            'jumlah_retur' => 'required|integer|min:1',
            'tipe_retur' => 'required|in:masuk_stok,buang_rusak',
            'tanggal_retur' => 'required|date',
            'alasan_retur' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => 'Validasi input gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $totalTerjual = \App\Models\Penjualan::where('produk_id', $request->produk_id)->sum('jumlah_terjual');
        $totalTelahDiretur = \App\Models\Retur::where('produk_id', $request->produk_id)->sum('jumlah_retur');
        $maksimalRetur = $totalTerjual - $totalTelahDiretur;

        if ($request->jumlah_retur > $maksimalRetur) {
            return response()->json([
                'error' => true,
                'message' => 'Validasi batas retur gagal',
                'errors' => [
                    'jumlah_retur' => ["Barang yang diretur melebihi batasan. Sisa barang yang bisa diretur untuk produk ini maksimal hanya {$maksimalRetur} Pcs."]
                ]
            ], 422);
        }

        DB::beginTransaction();

        try {
            $retur = Retur::create([
                'produk_id' => $request->produk_id,
                'jumlah_retur' => $request->jumlah_retur,
                'tipe_retur' => $request->tipe_retur,
                'tanggal_retur' => $request->tanggal_retur,
                'alasan_retur' => $request->alasan_retur,
            ]);

            // Logika Update Stok
            $produk = Produk::findOrFail($request->produk_id);

            // Jika tipe retur adalah masuk_stok, barang dikembalikan ke rak
            if ($request->tipe_retur == 'masuk_stok') {
                $produk->increment('stok_saat_ini', $request->jumlah_retur);
            }

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => 'Retur berhasil disimpan',
                'data' => $retur
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => true,
                'message' => 'Gagal menyimpan retur',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    // public function show($id)
    // {
    //     $retur = Retur::with('produk')->find($id);

    //     if (!$retur) {
    //         return response()->json([
    //             'error' => true,
    //             'message' => 'Data retur tidak ditemukan'
    //         ], 404);
    //     }

    //     return response()->json([
    //         'error' => false,
    //         'message' => 'Detail data retur berhasil diambil',
    //         'data' => $retur
    //     ], 200);
    // }

    // public function destroy($id)
    // {
    //     $retur = Retur::find($id);

    //     if (!$retur) {
    //         return response()->json([
    //             'error' => true,
    //             'message' => 'Data retur tidak ditemukan'
    //         ], 404);
    //     }

    //     try {
    //         $retur->delete();

    //         return response()->json([
    //             'error' => false,
    //             'message' => 'Data transaksi retur berhasil dihapus'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => true,
    //             'message' => 'Gagal menghapus data retur',
    //             'exception' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
