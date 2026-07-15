<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kader extends Model
{
    protected $fillable = [
        'posyandu_id',
        'penduduk_id',
        'jabatan',
        'tanggal_mulai_tugas',
        'status_aktif',
        'pelatihan',
        'keterangan',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'tanggal_mulai_tugas' => 'date',
    ];

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }
}
