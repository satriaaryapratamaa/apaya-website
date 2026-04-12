<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualans';

    protected $fillable = [
        'penjualans_id',
        'produks_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualans_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class,'produks_id');
    }
}
