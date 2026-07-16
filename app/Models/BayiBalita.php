<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class BayiBalita extends Model
{
    protected $guarded = [];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function pemeriksaans()
    {
        return $this->hasMany(PemeriksaanBalita::class, 'bayi_balita_id');
    }

    public function imunisasis()
    {
        return $this->hasMany(ImunisasiBalita::class, 'bayi_balita_id');
    }

    public function getUmurBulanAttribute()
    {
        if (!$this->tanggal_lahir) {
            return 0;
        }
        return Carbon::parse($this->tanggal_lahir)->diffInMonths(Carbon::now());
    }
}
