<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanBalita extends Model
{
    protected $table = 'pemeriksaan_balitas';
    protected $guarded = [];

    public function bayiBalita()
    {
        return $this->belongsTo(BayiBalita::class, 'bayi_balita_id');
    }
}
