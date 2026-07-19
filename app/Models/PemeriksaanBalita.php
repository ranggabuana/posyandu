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

    public function getZScoreBbUAttribute()
    {
        if (!$this->berat_badan || $this->umur_bulan === null) return null;
        $jk = strtolower($this->bayiBalita->penduduk->kelamin ?? 'laki-laki');
        if ($jk === 'laki-laki' || $jk === 'l') {
            $medianBB = $this->umur_bulan <= 12 
                ? 3.3 + ($this->umur_bulan * 0.58) 
                : 10.3 + (($this->umur_bulan - 12) * 0.18);
        } else {
            $medianBB = $this->umur_bulan <= 12 
                ? 3.2 + ($this->umur_bulan * 0.55) 
                : 9.7 + (($this->umur_bulan - 12) * 0.18);
        }
        $sdBB = $medianBB * 0.12;
        $zBB = ($this->berat_badan - $medianBB) / $sdBB;
        return round($zBB, 2);
    }

    public function getZScoreTbUAttribute()
    {
        if (!$this->tinggi_badan || $this->umur_bulan === null) return null;
        $jk = strtolower($this->bayiBalita->penduduk->kelamin ?? 'laki-laki');
        if ($jk === 'laki-laki' || $jk === 'l') {
            $medianTB = $this->umur_bulan <= 12 
                ? 50.5 + ($this->umur_bulan * 2.1) 
                : 75.7 + (($this->umur_bulan - 12) * 0.65);
        } else {
            $medianTB = $this->umur_bulan <= 12 
                ? 49.8 + ($this->umur_bulan * 2.1) 
                : 74.0 + (($this->umur_bulan - 12) * 0.66);
        }
        $sdTB = $medianTB * 0.045;
        $zTB = ($this->tinggi_badan - $medianTB) / $sdTB;
        return round($zTB, 2);
    }
}
