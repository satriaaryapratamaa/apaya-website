<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelians';

    protected $fillable = [
        'produks_id',
        'jumlah_masuk',
        'harga_beli_satuan', // Untuk melacak jika ada perubahan harga dari supplier
        'tanggal_pembelian',
        'keterangan',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produks_id');
    }
}
