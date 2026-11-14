<?php

namespace App\Services;

use App\Models\Pengaturan;
use Illuminate\Support\Facades\Cache;

class ApiSettingsService
{
    public const CACHE_KEY = 'posyandu_api_settings';

    public const DEFAULTS = [
        'api_enabled' => [
            'value' => '1',
            'label' => 'Status REST API',
            'keterangan' => 'Aktifkan atau nonaktifkan seluruh endpoint REST API',
        ],
        'api_auth_required' => [
            'value' => '1',
            'label' => 'Wajib API Key / Token',
            'keterangan' => 'Jika aktif, setiap request wajib menyertakan X-API-Key atau Bearer Token',
        ],
        'api_rate_limit' => [
            'value' => '60',
            'label' => 'Rate Limit (Request/Menit)',
            'keterangan' => 'Batas maksimal request per menit per API Key atau IP',
        ],
        'api_ip_whitelist' => [
            'value' => '',
            'label' => 'IP Whitelist Global',
            'keterangan' => 'Daftar IP yang diizinkan (pisahkan koma/baris baru). Kosongkan untuk semua IP',
        ],
        'api_cors_origins' => [
            'value' => '*',
            'label' => 'CORS Allowed Origins',
            'keterangan' => 'Origin yang diizinkan (misal * atau https://domain.com)',
        ],
        'api_force_https' => [
            'value' => '0',
            'label' => 'Wajib HTTPS (Production)',
            'keterangan' => 'Tolak request HTTP non-HTTPS pada lingkungan production',
        ],
        'api_logging_enabled' => [
            'value' => '1',
            'label' => 'Log Aktivitas API',
            'keterangan' => 'Catat setiap akses API ke dalam log sistem',
        ],
        'api_mask_sensitive' => [
            'value' => '0',
            'label' => 'Samarkan Data Sensitif',
            'keterangan' => 'Samarkan NIK, No KK, dan Nomor Telepon pada response API',
        ],
        'api_default_per_page' => [
            'value' => '15',
            'label' => 'Default Item Per Halaman',
            'keterangan' => 'Jumlah data default per halaman untuk paginasi API',
        ],
        'api_max_per_page' => [
            'value' => '100',
            'label' => 'Maksimal Item Per Halaman',
            'keterangan' => 'Batas maksimal parameter per_page pada endpoint API',
        ],
    ];

    /**
     * Ensure all default API settings exist in pengaturans table and return key => value map.
     */
    public static function all(): array
    {
        return Cache::remember(self::CACHE_KEY, 300, function () {
            $keys = array_keys(self::DEFAULTS);
            $existing = Pengaturan::whereIn('key', $keys)->pluck('value', 'key')->toArray();

            $result = [];
            foreach (self::DEFAULTS as $key => $meta) {
                if (!array_key_exists($key, $existing)) {
                    Pengaturan::firstOrCreate(
                        ['key' => $key],
                        [
                            'value' => $meta['value'],
                            'label' => $meta['label'],
                            'keterangan' => $meta['keterangan'],
                        ]
                    );
                    $result[$key] = $meta['value'];
                } else {
                    $result[$key] = $existing[$key] ?? $meta['value'];
                }
            }

            return $result;
        });
    }

    public static function get(string $key, $default = null)
    {
        $all = self::all();
        return $all[$key] ?? ($default ?? (self::DEFAULTS[$key]['value'] ?? null));
    }

    public static function updateMany(array $values): void
    {
        foreach (self::DEFAULTS as $key => $meta) {
            if (array_key_exists($key, $values)) {
                Pengaturan::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => (string) ($values[$key] ?? ''),
                        'label' => $meta['label'],
                        'keterangan' => $meta['keterangan'],
                    ]
                );
            }
        }
        Cache::forget(self::CACHE_KEY);
    }
}

