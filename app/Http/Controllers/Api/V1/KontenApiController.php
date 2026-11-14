<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Jadwal;
use App\Models\Tim;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KontenApiController extends BaseApiController
{
    public function beritas(Request $request): JsonResponse
    {
        $query = Berita::query()->latest('id');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('judul', 'like', "%{$s}%")->orWhere('isi', 'like', "%{$s}%"));
        }
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data berita.');
    }

    public function beritaShow($id): JsonResponse
    {
        $item = Berita::find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Berita tidak ditemukan.');
    }

    public function galeries(Request $request): JsonResponse
    {
        $query = Galeri::query()->latest('id');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('judul', 'like', "%{$s}%");
        }
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data galeri.');
    }

    public function galeriShow($id): JsonResponse
    {
        $item = Galeri::find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Galeri tidak ditemukan.');
    }

    public function jadwals(Request $request): JsonResponse
    {
        $query = Jadwal::query()->latest('id');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('kegiatan', 'like', "%{$s}%");
        }
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data jadwal pelayanan.');
    }

    public function jadwalShow($id): JsonResponse
    {
        $item = Jadwal::find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Jadwal tidak ditemukan.');
    }

    public function tims(Request $request): JsonResponse
    {
        $query = Tim::query()->latest('id');
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('nama', 'like', "%{$s}%");
        }
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data tim.');
    }

    public function timShow($id): JsonResponse
    {
        $item = Tim::find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Data tim tidak ditemukan.');
    }
}

