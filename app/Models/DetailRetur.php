<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailRetur extends Model
{
    protected $fillable = [
        'retur_id',
        'produk_id',
        'jumlah_retur',
        'harga_retur',
    ];

    public function retur()
    {
        return $this->belongsTo(Retur::class);
    }
    
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
