<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tim extends Model
{
    protected $fillable = [
        'nama',
        'jabatan',
        'deskripsi',
        'foto',
    ];
}
