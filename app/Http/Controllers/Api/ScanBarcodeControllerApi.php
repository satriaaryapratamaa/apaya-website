<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;

class ScanBarcodeControllerApi extends Controller
{
    public function scanBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $hasilScan = $request->input('barcode');

        // mencari produk berdasarkan barcode
        $produk = Produk::where('sku', $hasilScan)->first();

        if ($produk) {
            return response()->json([
                'success' => true,
                'message' => 'Produk ditemukan',
                'data' => $produk
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Produk ini tidak ditemukan',
            ], 404);
        }
    }
}
