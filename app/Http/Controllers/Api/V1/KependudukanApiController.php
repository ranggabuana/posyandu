<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Penduduk;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KependudukanApiController extends BaseApiController
{
    public function penduduks(Request $request): JsonResponse
    {
        $query = Penduduk::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%")
                  ->orWhere('no_kk', 'like', "%{$s}%");
            });
        }

        foreach (['dusun', 'rw', 'rt', 'kelamin'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->input($field));
            }
        }

        $query->orderBy($request->input('sort', 'id'), $request->input('direction', 'desc'));

        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data penduduk.');
    }

    public function pendudukShow($id): JsonResponse
    {
        $penduduk = Penduduk::find($id);
        if (!$penduduk) {
            return $this->errorResponse('Data penduduk tidak ditemukan.', 404);
        }

        return $this->successResponse($penduduk, 'Berhasil mengambil detail penduduk.');
    }

    public function ibus(Request $request): JsonResponse
    {
        $query = Penduduk::where('kelamin', 'perempuan')
            ->where(function ($q) {
                $q->where('status_kawin', '!=', 'belum kawin')
                  ->orWhereIn('nama', function ($sub) {
                      $sub->select('nama_ibu')->from('penduduks')->whereNotNull('nama_ibu');
                  })
                  ->orWhereIn('nama', function ($sub) {
                      $sub->select('nama_ibu')->from('bayi_balitas')->whereNotNull('nama_ibu');
                  });
            });

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%");
            });
        }

        foreach (['dusun', 'rw', 'rt'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->input($field));
            }
        }

        $query->orderBy('nama', 'asc');

        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data ibu.');
    }
}

