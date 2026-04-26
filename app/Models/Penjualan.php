<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualans';

    protected $fillable = [
        'produk_id',
        'tanggal_penjualan',
        'jumlah_terjual',
        'harga_jual',
        'total_omzet',
        'total_modal',
        'total_keuntungan',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function returs()
    {
        return $this->hasMany(Retur::class, 'produk_id', 'produk_id');
    }
}
