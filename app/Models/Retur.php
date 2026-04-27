<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Retur extends Model
{
    protected $fillable = [
        'produk_id',
        'jumlah_retur',
        'tipe_retur',
        'tanggal_retur',
        'alasan_retur',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
