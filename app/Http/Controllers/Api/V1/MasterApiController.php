<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Kader;
use App\Models\Pengaturan;
use App\Models\Posyandu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MasterApiController extends BaseApiController
{
    public function posyandus(Request $request): JsonResponse
    {
        $query = Posyandu::with(['kaders.penduduk'])->orderBy('nama', 'asc');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('nama', 'like', "%{$s}%")->orWhere('dusun', 'like', "%{$s}%"));
        }
        if ($request->filled('dusun')) {
            $query->where('dusun', $request->dusun);
        }
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data posyandu.');
    }

    public function posyanduShow($id): JsonResponse
    {
        $item = Posyandu::with(['kaders.penduduk'])->find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Data posyandu tidak ditemukan.');
    }

    public function kaders(Request $request): JsonResponse
    {
        $query = Kader::with(['posyandu', 'penduduk'])->latest('id');
        if ($request->filled('posyandu_id')) {
            $query->where('posyandu_id', $request->posyandu_id);
        }
        if ($request->filled('status_aktif')) {
            $query->where('status_aktif', filter_var($request->status_aktif, FILTER_VALIDATE_BOOLEAN));
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('penduduk', fn ($q) => $q->where('nama', 'like', "%{$s}%")->orWhere('nik', 'like', "%{$s}%"));
        }
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data kader.');
    }

    public function kaderShow($id): JsonResponse
    {
        $item = Kader::with(['posyandu', 'penduduk'])->find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Data kader tidak ditemukan.');
    }

    public function pengaturans(Request $request): JsonResponse
    {
        $query = Pengaturan::where('key', 'not like', 'api_%')->orderBy('key', 'asc');
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data pengaturan sistem.');
    }
}

