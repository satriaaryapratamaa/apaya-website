<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retur;
use App\Models\Produk;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    public function index()
    {
        $returs = Retur::with('produk')->latest()->get();

        return view('retur.index', compact('returs'));
    }

    public function create()
    {
        // Ambil daftar produk untuk dipilih yang mana yang diretur
        $produks = Produk::all();
        return view('retur.create', compact('produks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'produk_id'     => 'required|exists:produks,id',
            'jumlah_retur'  => 'required|integer|min:1',
            'tipe_retur'    => 'required|in:masuk_stok,buang_rusak',
            'tanggal_retur' => 'required|date',
            'alasan_retur'  => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $request->wantsJson()
                ? response()->json(['errors' => $validator->errors()], 422)
                : redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            // Simpan data retur (Langsung per produk, sesuai revisi tanpa nota)
            $retur = Retur::create([
                'produk_id'     => $request->produk_id,
                'jumlah_retur'  => $request->jumlah_retur,
                'tipe_retur'    => $request->tipe_retur,
                'tanggal_retur' => $request->tanggal_retur,
                'alasan_retur'  => $request->alasan_retur,
            ]);

            // Logika Update Stok
            $produk = Produk::findOrFail($request->produk_id);

            // Jika tipe retur adalah 'masuk_stok' (barang kembali ke toko dan bisa dijual lagi)
            if ($request->tipe_retur == 'masuk_stok') {
                $produk->increment('stok', $request->jumlah_retur);
            }
            // Jika tipe 'buang_rusak', stok tidak bertambah karena barang dibuang.
            // Data tetap disimpan di tabel retur sebagai catatan log kenapa stok berkurang/hilang.

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Retur berhasil disimpan',
                    'data' => $retur->load('produk')
                ], 201);
            }

            return redirect()->route('retur.index')->with('success', 'Data retur berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();

            return $request->wantsJson()
                ? response()->json(['errors' => 'Gagal menyimpan retur: ' . $e->getMessage()], 500)
                : redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
