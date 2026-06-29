<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiketBantuan extends Model
{
    protected $table = 'tiket_bantuan';

    protected $fillable = [
        'no_tiket', 'user_id', 'judul', 'pesan', 'kategori', 'status',
        'balasan_admin', 'dijawab_oleh', 'dijawab_pada',
    ];

    protected static function booted(): void
    {
        static::creating(function ($tiket) {
            if ($tiket->no_tiket) {
                return;
            }

            $map = [
                'teknis' => 'TEK',
                'pembayaran' => 'PAY',
                'properti' => 'PRP',
                'akun' => 'AKN',
                'lainnya' => 'LNY',
            ];

            $prefix = 'TKT';
            $kategori = $map[$tiket->kategori] ?? 'XXX';
            $last = static::where('no_tiket', 'like', $prefix.'-'.$kategori.'-%')
                ->orderBy('no_tiket', 'desc')
                ->lockForUpdate()
                ->value('no_tiket');
            $next = $last ? (int) substr($last, -3) + 1 : 1;
            $tiket->no_tiket = $prefix.'-'.$kategori.'-'.str_pad($next, 3, '0', STR_PAD_LEFT);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penjawab()
    {
        return $this->belongsTo(Admin::class, 'dijawab_oleh');
    }
}
