<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IbuHamil extends Model
{
    protected $guarded = [];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function suami()
    {
        return $this->belongsTo(Penduduk::class, 'suami_id');
    }
}
