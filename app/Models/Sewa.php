<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sewa extends Model
{
    protected $table = 'sewa';

    protected $fillable = [
        'penyewa_id', 'properti_id', 'tanggal_mulai', 'tanggal_selesai',
        'durasi_bulan', 'total_harga', 'biaya_layanan', 'status_sewa', 'disetujui_pada',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'disetujui_pada' => 'datetime',
            'total_harga' => 'decimal:2',
            'biaya_layanan' => 'decimal:2',
            'durasi_bulan' => 'integer',
        ];
    }

    public function penyewa()
    {
        return $this->belongsTo(User::class, 'penyewa_id');
    }

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'properti_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'sewa_id');
    }
}
