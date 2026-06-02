<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balita extends Model
{
    protected $guarded = [];

    public function bayiBalita()
    {
        return $this->belongsTo(BayiBalita::class, 'bayi_balita_id');
    }
}
