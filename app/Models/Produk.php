<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produks';

    protected $fillable = [
        'nama_produk',
        'sku',
        'harga_beli',
        'harga_jual',
        'stok_saat_ini',
    ];

    public function pembelians(): HasMany
    {
        return $this->hasMany(Pembelian::class, 'produk_id');
    }

    public function returs(): HasMany
    {
        return $this->hasMany(Retur::class, 'produk_id');
    }
}
