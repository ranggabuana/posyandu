<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'key_prefix',
        'key_hash',
        'abilities',
        'allowed_ips',
        'rate_limit',
        'is_active',
        'expires_at',
        'last_used_at',
        'last_used_ip',
        'hit_count',
        'notes',
    ];

    protected $hidden = [
        'key_hash',
    ];

    protected $casts = [
        'abilities' => 'array',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
        'rate_limit' => 'integer',
        'hit_count' => 'integer',
    ];

    public const AVAILABLE_ABILITIES = [
        '*' => 'Akses Penuh (Semua Data)',
        'read:statistik' => 'Statistik & Ringkasan',
        'read:penduduk' => 'Kependudukan (Penduduk & Ibu)',
        'read:kesehatan' => 'Data Kesehatan (Bumil, Bayi, Balita, Remaja, Lansia, WUS, PUS)',
        'read:konten' => 'Konten Publik (Berita, Galeri, Jadwal, Tim)',
        'read:interaksi' => 'Interaksi (Buku Tamu & Laporan Masyarakat)',
        'read:master' => 'Data Master (Posyandu, Kader, Pengaturan)',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(ApiLog::class);
    }

    /**
     * Generate a new plain API key, prefix, and SHA-256 hash.
     */
    public static function generateKey(): array
    {
        $plain = 'psy_live_' . Str::random(40);
        return [
            'plain' => $plain,
            'prefix' => substr($plain, 0, 16) . '...',
            'hash' => hash('sha256', $plain),
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function hasAbility(string $ability): bool
    {
        $abilities = $this->abilities ?? [];
        if (in_array('*', $abilities, true)) {
            return true;
        }
        return in_array($ability, $abilities, true);
    }

    public function isIpAllowed(string $ip): bool
    {
        if (empty(trim((string) $this->allowed_ips))) {
            return true;
        }
        $allowed = preg_split('/[\s,]+/', trim($this->allowed_ips), -1, PREG_SPLIT_NO_EMPTY);
        return in_array($ip, $allowed, true);
    }
}

