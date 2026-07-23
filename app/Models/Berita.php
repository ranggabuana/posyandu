<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'penulis');
    }

    public function getPenulisNamaAttribute()
    {
        if (!$this->user) {
            return 'Admin';
        }

        if ($this->user->hasRole('posyandu') && $this->user->posyandu) {
            return $this->user->posyandu->nama;
        }

        if ($this->user->posyandu) {
            return $this->user->posyandu->nama;
        }

        return 'Admin';
    }
}
