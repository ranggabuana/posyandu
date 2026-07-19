<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanRemaja extends Model
{
    protected $guarded = [];

    public function remaja()
    {
        return $this->belongsTo(Remaja::class);
    }
}
