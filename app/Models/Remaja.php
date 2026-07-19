<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remaja extends Model
{
    protected $guarded = [];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function pemeriksaans()
    {
        return $this->hasMany(PemeriksaanRemaja::class, 'remaja_id');
    }
}
