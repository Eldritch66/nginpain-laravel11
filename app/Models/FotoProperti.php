<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class FotoProperti extends Model
{
    protected $table = 'foto_properti';

    protected $fillable = [
        'properti_id', 'url',
    ];

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => url($value),
        );
    }

    public function properti()
    {
        return $this->belongsTo(Properti::class, 'properti_id');
    }
}
