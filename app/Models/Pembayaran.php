<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'kode_bayar', 'sewa_id', 'jumlah', 'metode', 'status', 'periode_bulan', 'dibayar_pada',
    ];

    protected static function booted(): void
    {
        static::creating(function ($pembayaran) {
            if ($pembayaran->kode_bayar) {
                return;
            }

            $map = [
                'QRIS' => 'QRS',
                'Transfer BCA' => 'BCA',
                'PayPal' => 'PYP',
            ];

            $prefix = 'PAY';
            $method = $map[$pembayaran->metode] ?? 'XXX';
            $last = static::where('kode_bayar', 'like', $prefix.'-'.$method.'-%')
                ->orderBy('kode_bayar', 'desc')
                ->lockForUpdate()
                ->value('kode_bayar');
            $next = $last ? (int) substr($last, -3) + 1 : 1;
            $pembayaran->kode_bayar = $prefix.'-'.$method.'-'.str_pad($next, 3, '0', STR_PAD_LEFT);
        });
    }

    public function sewa()
    {
        return $this->belongsTo(Sewa::class, 'sewa_id');
    }
}
