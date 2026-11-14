<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'api_key_id',
        'method',
        'endpoint',
        'ip_address',
        'user_agent',
        'status_code',
        'response_time_ms',
        'error_message',
        'created_at',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'response_time_ms' => 'integer',
        'created_at' => 'datetime',
    ];

    public function apiKey()
    {
        return $this->belongsTo(ApiKey::class);
    }
}

