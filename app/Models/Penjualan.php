<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualans';

    protected $fillable = [
        'nomor_invoice',
        'tanggal_penjualan',
        'total_bayar',
        'customer_name',
        'status',
    ];

    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualans_id');
    }

    public function returs()
    {
        return $this->hasMany(Retur::class);
    }
}
