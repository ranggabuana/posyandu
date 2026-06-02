<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $table = 'galeries';
    protected $guarded = [];

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }
}
