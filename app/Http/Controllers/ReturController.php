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
            return $request->wantsJson()
                ? response()->json(['errors' => $validator->errors()], 422)
                : redirect()->back()->withErrors($validator)->withInput();
        }

        $totalTerjual = \App\Models\Penjualan::where('produk_id', $request->produk_id)->sum('jumlah_terjual');
        $totalTelahDiretur = \App\Models\Retur::where('produk_id', $request->produk_id)->sum('jumlah_retur');
        $maksimalRetur = $totalTerjual - $totalTelahDiretur;

        if ($request->jumlah_retur > $maksimalRetur) {
            $pesanEror = "Validasi Gagal: Barang yang diretur melebihi batasan. Sisa barang yang bisa diretur untuk produk ini maksimal hanya {$maksimalRetur} Pcs.";
            return $request->wantsJson()
                ? response()->json(['errors' => ['jumlah_retur' => [$pesanEror]]], 422)
                : redirect()->back()->with('error', $pesanEror)->withInput();
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
