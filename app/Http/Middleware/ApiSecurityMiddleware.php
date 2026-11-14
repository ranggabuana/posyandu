<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use App\Models\ApiLog;
use App\Services\ApiSettingsService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ApiSecurityMiddleware
{
    public function handle(Request $request, Closure $next, ?string $requiredAbility = null): Response
    {
        $startTime = microtime(true);
        $settings = ApiSettingsService::all();

        // Handle CORS preflight
        if ($request->isMethod('OPTIONS')) {
            return $this->applyCorsHeaders(response('', 204), $request, $settings);
        }

        $apiKey = null;

        // 1. Check if REST API is enabled globally
        if (($settings['api_enabled'] ?? '1') !== '1') {
            return $this->finalize(
                $request,
                $this->errorJson('REST API sedang dinonaktifkan oleh administrator.', 503),
                $settings,
                null,
                $startTime,
                'API disabled'
            );
        }

        // 2. Enforce HTTPS in production if enabled
        if (($settings['api_force_https'] ?? '0') === '1' && app()->environment('production') && !$request->secure()) {
            return $this->finalize(
                $request,
                $this->errorJson('Akses API wajib menggunakan protokol HTTPS.', 403),
                $settings,
                null,
                $startTime,
                'HTTPS required'
            );
        }

        // 3. Global IP Whitelist check
        $globalWhitelist = trim((string) ($settings['api_ip_whitelist'] ?? ''));
        if ($globalWhitelist !== '') {
            $allowedIps = preg_split('/[\s,]+/', $globalWhitelist, -1, PREG_SPLIT_NO_EMPTY);
            if (!in_array($request->ip(), $allowedIps, true)) {
                return $this->finalize(
                    $request,
                    $this->errorJson('IP Anda (' . $request->ip() . ') tidak diizinkan mengakses API ini.', 403),
                    $settings,
                    null,
                    $startTime,
                    'IP not in global whitelist'
                );
            }
        }

        // 4. API Key Authentication
        $token = $this->extractToken($request);
        $authRequired = ($settings['api_auth_required'] ?? '1') === '1';

        if ($authRequired && empty($token)) {
            return $this->finalize(
                $request,
                $this->errorJson('Autentikasi diperlukan. Sertakan header X-API-Key atau Authorization: Bearer <token>.', 401),
                $settings,
                null,
                $startTime,
                'Missing API key'
            );
        }

        if (!empty($token)) {
            $apiKey = ApiKey::where('key_hash', hash('sha256', $token))->first();

            if (!$apiKey) {
                return $this->finalize(
                    $request,
                    $this->errorJson('API Key tidak valid.', 401),
                    $settings,
                    null,
                    $startTime,
                    'Invalid API key'
                );
            }

            if (!$apiKey->is_active) {
                return $this->finalize(
                    $request,
                    $this->errorJson('API Key telah dinonaktifkan.', 403),
                    $settings,
                    $apiKey,
                    $startTime,
                    'Inactive API key'
                );
            }

            if ($apiKey->isExpired()) {
                return $this->finalize(
                    $request,
                    $this->errorJson('API Key telah kedaluwarsa.', 401),
                    $settings,
                    $apiKey,
                    $startTime,
                    'Expired API key'
                );
            }

            if (!$apiKey->isIpAllowed((string) $request->ip())) {
                return $this->finalize(
                    $request,
                    $this->errorJson('IP Anda tidak diizinkan menggunakan API Key ini.', 403),
                    $settings,
                    $apiKey,
                    $startTime,
                    'IP not allowed for API key'
                );
            }

            if ($requiredAbility && !$apiKey->hasAbility($requiredAbility)) {
                return $this->finalize(
                    $request,
                    $this->errorJson("API Key tidak memiliki izin '{$requiredAbility}' untuk endpoint ini.", 403),
                    $settings,
                    $apiKey,
                    $startTime,
                    "Missing ability: {$requiredAbility}"
                );
            }
        }

        // 5. Rate Limiting
        $maxAttempts = $apiKey && $apiKey->rate_limit
            ? (int) $apiKey->rate_limit
            : max(1, (int) ($settings['api_rate_limit'] ?? 60));

        $rateKey = $apiKey
            ? 'api_rate:key:' . $apiKey->id
            : 'api_rate:ip:' . $request->ip();

        if (RateLimiter::tooManyAttempts($rateKey, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($rateKey);
            $response = $this->errorJson("Batas rate limit ({$maxAttempts} req/menit) terlampaui. Coba lagi dalam {$retryAfter} detik.", 429);
            $response->headers->set('Retry-After', (string) $retryAfter);
            $response->headers->set('X-RateLimit-Limit', (string) $maxAttempts);
            $response->headers->set('X-RateLimit-Remaining', '0');

            return $this->finalize($request, $response, $settings, $apiKey, $startTime, 'Rate limit exceeded');
        }

        RateLimiter::hit($rateKey, 60);
        $remaining = max(0, $maxAttempts - RateLimiter::attempts($rateKey));

        // Update API Key usage stats
        if ($apiKey) {
            $apiKey->forceFill([
                'last_used_at' => now(),
                'last_used_ip' => $request->ip(),
                'hit_count' => $apiKey->hit_count + 1,
            ])->saveQuietly();
        }

        /** @var Response $response */
        $response = $next($request);
        $response->headers->set('X-RateLimit-Limit', (string) $maxAttempts);
        $response->headers->set('X-RateLimit-Remaining', (string) $remaining);

        return $this->finalize($request, $response, $settings, $apiKey, $startTime);
    }

    private function extractToken(Request $request): ?string
    {
        $headerKey = $request->header('X-API-Key');
        if (!empty($headerKey)) {
            return trim($headerKey);
        }

        $bearer = $request->bearerToken();
        if (!empty($bearer)) {
            return trim($bearer);
        }

        $queryKey = $request->query('api_key');
        if (is_string($queryKey) && $queryKey !== '') {
            return trim($queryKey);
        }

        return null;
    }

    private function errorJson(string $message, int $status): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'status_code' => $status,
        ], $status);
    }

    private function applyCorsHeaders(Response $response, Request $request, array $settings): Response
    {
        $configured = trim((string) ($settings['api_cors_origins'] ?? '*'));
        $origin = $request->header('Origin');

        if ($configured === '*' || $configured === '') {
            $response->headers->set('Access-Control-Allow-Origin', '*');
        } elseif ($origin) {
            $allowedOrigins = array_map('trim', explode(',', $configured));
            if (in_array($origin, $allowedOrigins, true)) {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                $response->headers->set('Vary', 'Origin');
            }
        }

        $response->headers->set('Access-Control-Allow-Methods', 'GET, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Authorization, X-API-Key, Content-Type, Accept, X-Requested-With');
        $response->headers->set('Access-Control-Max-Age', '86400');

        return $response;
    }

    private function finalize(
        Request $request,
        Response $response,
        array $settings,
        ?ApiKey $apiKey,
        float $startTime,
        ?string $errorMessage = null
    ): Response {
        $response = $this->applyCorsHeaders($response, $request, $settings);

        if (($settings['api_logging_enabled'] ?? '1') === '1') {
            try {
                $durationMs = (int) round((microtime(true) - $startTime) * 1000);
                ApiLog::create([
                    'api_key_id' => $apiKey?->id,
                    'method' => $request->method(),
                    'endpoint' => '/' . ltrim($request->path(), '/'),
                    'ip_address' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 500),
                    'status_code' => $response->getStatusCode(),
                    'response_time_ms' => $durationMs,
                    'error_message' => $errorMessage,
                    'created_at' => now(),
                ]);
            } catch (\Throwable $e) {
                // Do not break API response if logging fails
            }
        }

        return $response;
    }
}

