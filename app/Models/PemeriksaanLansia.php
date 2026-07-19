<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanLansia extends Model
{
    protected $table = 'pemeriksaan_lansias';
    protected $guarded = [];

    public function lansia()
    {
        return $this->belongsTo(Lansia::class, 'lansia_id');
    }
}
