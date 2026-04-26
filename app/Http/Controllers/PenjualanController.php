<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{
    public function index()
    {
        $penjualans = Penjualan::with('produk')->latest()->get();

        if (request()->wantsJson()) {
            return response()->json($penjualans, 200);
        }
        return view('penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        // Menampilkan semua produk untuk dicek sisa stoknya
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
            'items.*.stok_sisa' => 'required|integer|min:0', // User input sisa di rak
        ]);

        if ($validator->fails()) {
            return $request->wantsJson()
                ? response()->json(['errors' => $validator->errors()], 422)
                : redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['produk_id']);

                // Ambil stok awal (stok saat ini di database sebelum update)
                $stok_awal = $produk->stok;

                // Ambil total pembelian hari ini (kalau ada)
                $total_pembelian = Pembelian::where('produk_id', $produk->id)
                    ->whereDate('tanggal_pembelian', $request->tanggal_penjualan)
                    ->sum('jumlah_masuk');

                // Hitung jumlah terjual: (Stok Awal + Pembelian) - Sisa Stok
                $jumlah_terjual = ($stok_awal + $total_pembelian) - $item['stok_sisa'];

                // Jika hasil negatif (stok sisa lebih banyak dari stok awal + beli), biarin atau set 0
                if ($jumlah_terjual > 0) {
                    $total_omzet = $jumlah_terjual * $produk->harga_jual;
                    $total_modal = $jumlah_terjual * $produk->harga_beli;
                    $total_keuntungan = $total_omzet - $total_modal;

                    // Simpan ke log penjualan harian
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

                // Update stok utama di tabel produks menjadi sisa stok terbaru
                $produk->update(['stok' => $item['stok_sisa']]);
            }

            DB::commit();

            return $request->wantsJson()
                ? response()->json(['message' => 'Laporan stok harian berhasil disimpan'], 201)
                : redirect()->route('penjualan.index')->with('success', 'Laporan stok harian berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            return $request->wantsJson()
                ? response()->json(['errors' => $e->getMessage()], 500)
                : redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}
