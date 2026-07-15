<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posyandu extends Model
{
    protected $fillable = [
        'nama', 'ketua', 'alamat', 'telepon', 'dusun', 'rw_diampu', 
        'jadwal_rutin', 'lokasi_koordinat', 'jumlah_kader', 'bangunan',
        'punya_timbangan_dacin', 'punya_timbangan_digital', 'punya_alat_ukur_tinggi',
        'punya_pita_liLa', 'punya_buku_kia', 'keterangan_lain'
    ];

    protected $casts = [
        'rw_diampu' => 'array',
        'punya_timbangan_dacin' => 'boolean',
        'punya_timbangan_digital' => 'boolean',
        'punya_alat_ukur_tinggi' => 'boolean',
        'punya_pita_liLa' => 'boolean',
        'punya_buku_kia' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function kaders()
    {
        return $this->hasMany(Kader::class);
    }

    public function galeries()
    {
        return $this->hasMany(Galeri::class);
    }
}
