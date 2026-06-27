<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Properti extends Model
{
    protected $table = 'properti';

    protected $fillable = [
        'pemilik_id', 'nama_properti', 'tipe', 'alamat', 'kota',
        'harga_per_bulan', 'harga_per_dua_bulan',
    ];

    public function pemilik()
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    public function foto()
    {
        return $this->hasMany(FotoProperti::class, 'properti_id');
    }

    public function unit()
    {
        return $this->hasOne(Unit::class, 'properti_id');
    }

    public function sewa()
    {
        return $this->hasMany(Sewa::class, 'properti_id');
    }
}
