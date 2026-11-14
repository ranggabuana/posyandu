<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ApiSettingsService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseApiController extends Controller
{
    protected const SENSITIVE_KEYS = ['nik', 'no_kk', 'no_telepon', 'telepon', 'no_hp'];

    protected function successResponse($data, string $message = 'Berhasil mengambil data', ?array $meta = null, int $status = 200): JsonResponse
    {
        $payload = [
            'success' => true,
            'message' => $message,
            'data' => $this->transformPayload($data),
        ];

        if ($meta !== null) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    protected function errorResponse(string $message, int $status = 404): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'status_code' => $status,
        ], $status);
    }

    protected function paginateOrAll(Builder $query, Request $request, string $message = 'Berhasil mengambil data'): JsonResponse
    {
        if ($request->boolean('all') || $request->input('per_page') === 'all') {
            $items = $query->get();
            return $this->successResponse($items, $message, [
                'total' => $items->count(),
                'all' => true,
            ]);
        }

        $defaultPerPage = max(1, (int) ApiSettingsService::get('api_default_per_page', 15));
        $maxPerPage = max(1, (int) ApiSettingsService::get('api_max_per_page', 100));
        $perPage = min($maxPerPage, max(1, (int) $request->input('per_page', $defaultPerPage)));

        $paginator = $query->paginate($perPage);

        return $this->successResponse($paginator->items(), $message, [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ]);
    }

    protected function transformPayload($data)
    {
        $array = json_decode(json_encode($data), true);

        if (ApiSettingsService::get('api_mask_sensitive', '0') === '1') {
            return $this->maskRecursive($array);
        }

        return $array;
    }

    private function maskRecursive($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->maskRecursive($value);
            } elseif (is_string($key) && in_array(strtolower($key), self::SENSITIVE_KEYS, true) && is_string($value) && $value !== '') {
                $data[$key] = $this->maskString($value);
            }
        }

        return $data;
    }

    private function maskString(string $value): string
    {
        $len = strlen($value);
        if ($len <= 4) {
            return str_repeat('*', $len);
        }
        if ($len <= 8) {
            return substr($value, 0, 2) . str_repeat('*', $len - 4) . substr($value, -2);
        }
        return substr($value, 0, 4) . str_repeat('*', $len - 8) . substr($value, -4);
    }
}

