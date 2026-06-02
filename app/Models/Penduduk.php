<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    protected $guarded = [];

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }
}
