<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retur;
use App\Models\Penjualan;
use App\Models\DetailRetur;
use App\Models\DetailPenjualan;
use App\Models\Produk;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    public function index()
    {
        $returs = Retur::with('details.produk')->latest()->get();

        return view('retur.index', compact('returs'));
    }

    public function create()
    {
        $penjualans = Penjualan::whereMonth('tanggal_penjualan', now()->month)->get();
        return view('retur.create', compact('penjualans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'penjualan_id' => 'required|exists:penjualans,id',
            'tanggal_retur' => 'required|date',
            'total_retur' => 'required|numeric',
            'alasan_retur' => 'nullable|string',
            'items' => 'required|array',
            'items.*.produk_id' => 'required|exists:produks,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric',
        ]);

        //validasi untuk mobile
        if ($validator->fails()){
            if($request->wantsJson()){
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
        }

        DB::beginTransaction();

        try {
            //simpan data retur
            $retur = Retur::create([
                'penjualan_id' => $request->penjualan_id,
                'tanggal_retur' => $request->tanggal_retur,
                'total_retur' => $request->total_retur,
                'alasan_retur' => $request->alasan_retur,
            ]);

            //simpan detail retur dan kembalikan stok barang
            foreach ($request->items as $item) {
                DetailRetur::create([
                    'retur_id' => $retur->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah_retur' => $item['qty'],
                ]);

                //cari data pembelian
                $detailpenjualan = DetailPenjualan::where('penjualan_id', $request->penjualan_id)
                    ->where('produk_id', $item['produk_id'])->first();

                if (!$detailpenjualan){
                    throw new \Exception('Data penjualan produk tidak ditemukan');
                }

                if ($item['qty'] > $detailpenjualan->jumlah) {
                    throw new \Exception('Jumlah barang yang diretur melebihi jumlah barang yang dibeli');
                }

                if (!$detailpenjualan->decrement('jumlah', $item['qty'])) {
                    throw new \Exception('Gagal mengupdate jumlah penjualan');
                }

                //kembalikan stok barang
                $produk = Produk::findOrFail($item['produk_id']);
                $produk->increment('stok', $item['qty']);;
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'retur berhasil disimpan',
                    'data' => $retur->load('details.produk')
                ], 201);
            }

            return redirect()->route('retur.index')->with('success', 'Data retur berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->wantsJson()) {
                return response()->json([
                    'errors' => 'Gagal menyimpan retur:' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'terjadi kesalahan saat menyimpan retur:' . $e->getMessage())->withInput();
        }

    }
}
