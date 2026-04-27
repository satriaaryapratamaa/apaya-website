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
            'items.*.stok_saat_ini_fisik' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $request->wantsJson()
                ? response()->json(['errors' => $validator->errors()], 422)
                : redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            foreach ($request->items as $item) {
                $produk = Produk::findOrFail($item['produk_id']);

                // Sesuai teks referensi: Stok Sebelumnya
                $stok_sebelumnya = $produk->stok_saat_ini ?? 0;
                
                // Sesuai teks referensi: Jumlah Pembelian
                $jumlah_pembelian = Pembelian::where('produks_id', $produk->id)
                    ->whereDate('tanggal_pembelian', $request->tanggal_penjualan)
                    ->sum('jumlah_masuk');

                // Sesuai teks referensi: Stok Saat Ini (dimasukkan dari input form fisik)
                $stok_saat_ini_fisik = $item['stok_saat_ini_fisik'];

                // Rumus Delta Dosen:
                // Total Penjualan = (Stok Sebelumnya + Jumlah Pembelian) - Stok Saat Ini
                $jumlah_terjual = ($stok_sebelumnya + $jumlah_pembelian) - $stok_saat_ini_fisik;

                if ($jumlah_terjual > 0) {
                    $total_omzet = $jumlah_terjual * $produk->harga_jual;
                    $total_modal = $jumlah_terjual * $produk->harga_beli;
                    $total_keuntungan = $total_omzet - $total_modal;

                    // Mengkalkulasi sistem log penjualan harian
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

                // Setelah perhitungan selesai, sistem mengupdate Master Data menjadi stok saat ini secara otomatis
                $produk->update(['stok_saat_ini' => $stok_saat_ini_fisik]);
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
