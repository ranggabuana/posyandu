<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wus extends Model
{
    protected $table = 'wus';
    protected $guarded = [];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }
}
