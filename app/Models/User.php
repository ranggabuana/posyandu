<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles {
        hasRole as traitHasRole;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nama_lengkap',
        'username',
        'email',
        'role',
        'password',
        'posyandu_id',
    ];

    /**
     * Check if user has a specific role (checks column first, then Spatie permission).
     */
    public function hasRole($roles, string $guard = null): bool
    {
        $userRole = strtolower($this->role ?? '');

        // If posyandu_id is null and role column says admin, user is definitely admin
        if (!$this->posyandu_id && $userRole === 'admin') {
            if (is_string($roles) && strtolower($roles) === 'admin') {
                return true;
            }
            if (is_string($roles) && strtolower($roles) === 'posyandu') {
                return false;
            }
        }

        // Check against role column
        if (is_string($roles)) {
            if (strtolower($roles) === $userRole) {
                return true;
            }
        } elseif (is_array($roles)) {
            foreach ($roles as $r) {
                if (strtolower($r) === $userRole) {
                    return true;
                }
            }
        }

        // Fallback to Spatie permission check
        try {
            return $this->traitHasRole($roles, $guard);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Scope query to find users by role (column or Spatie role).
     */
    public function scopeRole($query, $roles)
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }
        return $query->where(function($q) use ($roles) {
            $q->whereIn('role', $roles)
              ->orWhereHas('roles', function($rq) use ($roles) {
                  $rq->whereIn('name', $roles);
              });
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function beritas()
    {
        return $this->hasMany(Berita::class, 'penulis');
    }
}
