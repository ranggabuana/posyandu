<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\BukuTamu;
use App\Models\LaporanMasyarakat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InteraksiApiController extends BaseApiController
{
    public function bukuTamus(Request $request): JsonResponse
    {
        $query = BukuTamu::with(['posyandu'])->latest('id');
        if ($request->filled('posyandu_id')) {
            $query->where('posyandu_id', $request->posyandu_id);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('nama', 'like', "%{$s}%")->orWhere('instansi', 'like', "%{$s}%"));
        }
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data buku tamu.');
    }

    public function bukuTamuShow($id): JsonResponse
    {
        $item = BukuTamu::with(['posyandu'])->find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Data buku tamu tidak ditemukan.');
    }

    public function laporanMasyarakats(Request $request): JsonResponse
    {
        $query = LaporanMasyarakat::with(['posyandu'])->latest('id');
        if ($request->filled('posyandu_id')) {
            $query->where('posyandu_id', $request->posyandu_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('nama_pelapor', 'like', "%{$s}%")->orWhere('judul', 'like', "%{$s}%"));
        }
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data laporan masyarakat.');
    }

    public function laporanMasyarakatShow($id): JsonResponse
    {
        $item = LaporanMasyarakat::with(['posyandu'])->find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Laporan masyarakat tidak ditemukan.');
    }
}

