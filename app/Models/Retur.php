<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    protected $fillable = [
        'penjualan_id',
        'tanggal_retur',
        'total_retur',
        'alasan_retur',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
    
    public function details()
    {
        return $this->hasMany(DetailRetur::class);
    }
}
