<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'unit';

    protected $fillable = [
        'properti_id', 'luas_bangunan', 'jumlah_kamar_tidur', 'jumlah_kamar_mandi',
        'kapasitas_penghuni', 'lantai', 'keterangan',
    ];

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'properti_id');
    }
}
