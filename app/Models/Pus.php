<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pus extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function suami(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'suami_id');
    }

    public function istri(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'istri_id');
    }

    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }
}
