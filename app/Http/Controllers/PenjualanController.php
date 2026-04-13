<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{
    public  function index()
    {
        $penjualans = Penjualan::with('details.produk')->latest()->get();

        //request json untuk mobile app
        if (request()->wantsJson()) {
            return response()->json($penjualans, 200);
        }
        return view('penjualan.index', compact('penjualans'));
    }

    public function create()
    {
        $produks = Produk::where('stok', '>', 0)->get(); //mengambil data produk yang tersedia (stok > 0) untuk ditampilkan

        return view('penjualan.tambahPenjualan', compact('produks'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'tanggal_penjualan' => 'required|date',
            'customer_name'     => 'nullable|string|max:255',
            'status'            => 'required|in:lunas,hutang,dibatalkan',
            'items'             => 'required|array',
            'items.*.produks_id' => 'required|exists:produks,id',
            'items.*.qty'       => 'required|integer|min:1',
            'items.*.harga'     => 'required|numeric',
            'total_bayar'       => 'required|numeric'
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }


        try {
            //simpan data penjualan
            $penjualan = Penjualan::create([
                'nomor_invoice' => 'INV-' . strtoupper(uniqid()), //generate nomor invoice
                'tanggal_penjualan' => $request->tanggal_penjualan,
                'total_bayar' => $request->total_bayar,
                'customer_name' => $request->customer_name,
                'status' => $request->status
            ]);

            //simpan detail penjualaan
            foreach ($request->items as $item)
                {
                    $produk = Produk::findOrFail($item['produks_id']);

                    //cek stok produk
                    if ($produk->stok < $item['qty']) {
                        throw new \Exception('Stok produk'. $produk->nama_produk . 'tidak cukup');
                    }

                    DetailPenjualan::create([
                        'penjualans_id' => $penjualan->id,
                        'produks_id' => $item['produks_id'],
                        'jumlah' => $item['qty'],
                        'harga_satuan' => $item['harga'],
                        'subtotal' => $item['qty'] * $item['harga']
                    ]);

                    //update stok produk
                    $produk->decrement('stok', $item['qty']);
                }
            DB::commit();

            //reponse json
            if($request->wantsJson()){
                return response()->json(['message' => 'Penjualan berhaasil disimpan', 'data' => $penjualan->load('details.produk')], 201);
            }
            return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            if ($request->wantsJson()) {
                return response()->json(['errors' => 'terjadi kesalahan saat menambahkan penjualan' .$e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan penjualan:' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $detail_penjualan = Penjualan::with('details.produk')->findOrFail($id);

        return view('penjualan.index', compact('detail_penjualan'));
    }
}
