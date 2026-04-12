<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produks';

    protected $fillable = [
        'nama_produk',
        'sku',
        'harga_barang',
        'stok',
    ];

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function detailReturs()
    {
        return $this->hasMany(DetailRetur::class);
    }
}
