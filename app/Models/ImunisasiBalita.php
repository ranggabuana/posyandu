<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImunisasiBalita extends Model
{
    protected $table = 'imunisasi_balitas';
    protected $guarded = [];

    public function bayiBalita()
    {
        return $this->belongsTo(BayiBalita::class, 'bayi_balita_id');
    }
}
