<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BayiBalita extends Model
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

    public function balita()
    {
        return $this->hasOne(Balita::class, 'bayi_balita_id');
    }
}
