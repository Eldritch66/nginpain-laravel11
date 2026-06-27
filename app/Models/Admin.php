<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admin';

    protected $fillable = [
        'email', 'password', 'nama', 'last_login',
    ];

    protected $hidden = [
        'password',
    ];

    public function getNameAttribute()
    {
        return $this->nama;
    }
}
