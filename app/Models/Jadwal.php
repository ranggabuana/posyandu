<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = [
        'hari_tanggal',
        'jam_mulai',
        'jam_selesai',
        'kegiatan',
        'keterangan'
    ];
}
