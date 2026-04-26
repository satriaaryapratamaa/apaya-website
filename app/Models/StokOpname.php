<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokOpname extends Model
{
    protected $table = 'stok_opnames';

    protected $fillable = [
        'produk_id',
        'stok_awal',
        'stok_masuk',
        'stok_sisa',
        'jumlah_terjual',
        'tanggal_opname',
        'keterangan',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
