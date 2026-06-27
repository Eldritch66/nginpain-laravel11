<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'sewa_id', 'jumlah', 'metode', 'status', 'periode_bulan', 'dibayar_pada',
    ];

    public function sewa()
    {
        return $this->belongsTo(Sewa::class, 'sewa_id');
    }
}
