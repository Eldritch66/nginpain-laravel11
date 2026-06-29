<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'provider_id', 'no_hp', 'avatar_url',
    ];

    protected $hidden = [
        'password',
    ];

    public function isPemilik(): bool
    {
        return $this->role === 'pemilik';
    }

    public function isPenyewa(): bool
    {
        return $this->role === 'penyewa';
    }

    public function hasActiveSewa(): bool
    {
        return $this->sewa()->whereIn('status_sewa', ['aktif', 'pending'])->exists();
    }

    public function hasProperti(): bool
    {
        return $this->properti()->exists();
    }

    public function hasPropertiWithActiveSewa(): bool
    {
        return $this->properti()->whereHas('sewa', function ($q) {
            $q->whereIn('status_sewa', ['aktif', 'pending']);
        })->exists();
    }

    public function properti()
    {
        return $this->hasMany(Properti::class, 'pemilik_id');
    }

    public function sewa()
    {
        return $this->hasMany(Sewa::class, 'penyewa_id');
    }
}
