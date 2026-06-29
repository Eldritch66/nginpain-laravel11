<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Properti extends Model
{
    protected $table = 'properti';

    protected $fillable = [
        'pemilik_id', 'nama_properti', 'tipe', 'alamat', 'kota',
        'harga_per_bulan', 'harga_per_dua_bulan', 'kode_properti',
    ];

    protected static function booted(): void
    {
        static::creating(function ($properti) {
            if ($properti->kode_properti) {
                return;
            }

            $prefix = $properti->tipe === 'kost' ? 'KSN' : 'KNK';
            $last = static::where('tipe', $properti->tipe)
                ->orderBy('kode_properti', 'desc')
                ->lockForUpdate()
                ->value('kode_properti');
            $next = $last ? (int) substr($last, -3) + 1 : 1;
            $properti->kode_properti = $prefix.str_pad($next, 3, '0', STR_PAD_LEFT);
        });
    }

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
