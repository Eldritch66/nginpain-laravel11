<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiketBantuan extends Model
{
    protected $table = 'tiket_bantuan';

    protected $fillable = [
        'user_id', 'judul', 'pesan', 'kategori', 'status',
        'balasan_admin', 'dijawab_oleh', 'dijawab_pada',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penjawab()
    {
        return $this->belongsTo(Admin::class, 'dijawab_oleh');
    }
}
