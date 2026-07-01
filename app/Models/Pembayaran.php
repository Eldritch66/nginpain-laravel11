<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'kode_bayar', 'sewa_id', 'jumlah', 'metode', 'status', 'periode_bulan',
        'dibayar_pada', 'snap_token', 'midtrans_transaction_id',
    ];

    protected static function booted(): void
    {
        static::creating(function ($pembayaran) {
            if ($pembayaran->kode_bayar) {
                return;
            }

            $map = [
                'QRIS' => 'QRS',
                'qris' => 'QRS',
                'bank_transfer' => 'BTR',
                'credit_card' => 'CC',
                'gopay' => 'GOP',
                'shopeepay' => 'SHP',
                'other' => 'OTH',
            ];

            $prefix = 'PAY';
            $method = $map[$pembayaran->metode] ?? 'MDR';
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
