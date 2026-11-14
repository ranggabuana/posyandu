<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\BayiBalita;
use App\Models\IbuHamil;
use App\Models\ImunisasiBalita;
use App\Models\Lansia;
use App\Models\PemeriksaanBalita;
use App\Models\PemeriksaanLansia;
use App\Models\PemeriksaanRemaja;
use App\Models\Pus;
use App\Models\Remaja;
use App\Models\Wus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KesehatanApiController extends BaseApiController
{
    private function applyPendudukFilters($query, Request $request, string $relation = 'penduduk')
    {
        if ($request->filled('posyandu_id')) {
            $query->where('posyandu_id', $request->posyandu_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas($relation, function ($q) use ($s) {
                $q->where('nama', 'like', "%{$s}%")
                  ->orWhere('nik', 'like', "%{$s}%");
            });
        }

        foreach (['dusun', 'rw', 'rt'] as $loc) {
            if ($request->filled($loc)) {
                $val = $request->input($loc);
                $query->whereHas($relation, fn ($q) => $q->where($loc, $val));
            }
        }

        return $query;
    }

    public function ibuHamils(Request $request): JsonResponse
    {
        $query = IbuHamil::with(['penduduk', 'suami', 'posyandu'])->latest('id');
        $this->applyPendudukFilters($query, $request);
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data ibu hamil.');
    }

    public function ibuHamilShow($id): JsonResponse
    {
        $item = IbuHamil::with(['penduduk', 'suami', 'posyandu'])->find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Data ibu hamil tidak ditemukan.');
    }

    public function bayis(Request $request): JsonResponse
    {
        $query = BayiBalita::with(['penduduk', 'posyandu', 'pemeriksaans', 'imunisasis'])
            ->whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) <= 12')
            ->latest('id');
        $this->applyPendudukFilters($query, $request);
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data bayi (0-12 bulan).');
    }

    public function bayiShow($id): JsonResponse
    {
        $item = BayiBalita::with(['penduduk', 'posyandu', 'pemeriksaans', 'imunisasis'])->find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Data bayi tidak ditemukan.');
    }

    public function balitas(Request $request): JsonResponse
    {
        $query = BayiBalita::with(['penduduk', 'posyandu', 'pemeriksaans', 'imunisasis'])
            ->whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) > 12')
            ->whereRaw('TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) <= 60')
            ->latest('id');
        $this->applyPendudukFilters($query, $request);
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data balita (13-60 bulan).');
    }

    public function balitaShow($id): JsonResponse
    {
        $item = BayiBalita::with(['penduduk', 'posyandu', 'pemeriksaans', 'imunisasis'])->find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Data balita tidak ditemukan.');
    }

    public function bayiBalitas(Request $request): JsonResponse
    {
        $query = BayiBalita::with(['penduduk', 'posyandu', 'pemeriksaans', 'imunisasis'])->latest('id');
        $this->applyPendudukFilters($query, $request);
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil semua data bayi & balita.');
    }

    public function bayiBalitaShow($id): JsonResponse
    {
        $item = BayiBalita::with(['penduduk', 'posyandu', 'pemeriksaans', 'imunisasis'])->find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Data bayi/balita tidak ditemukan.');
    }

    public function pemeriksaanBalitas(Request $request): JsonResponse
    {
        $query = PemeriksaanBalita::with(['bayiBalita.penduduk'])->latest('tanggal_pemeriksaan');
        if ($request->filled('bayi_balita_id')) {
            $query->where('bayi_balita_id', $request->bayi_balita_id);
        }
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data pemeriksaan bayi & balita.');
    }

    public function imunisasiBalitas(Request $request): JsonResponse
    {
        $query = ImunisasiBalita::with(['bayiBalita.penduduk'])->latest('tanggal_imunisasi');
        if ($request->filled('bayi_balita_id')) {
            $query->where('bayi_balita_id', $request->bayi_balita_id);
        }
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data imunisasi bayi & balita.');
    }

    public function remajas(Request $request): JsonResponse
    {
        $query = Remaja::with(['penduduk', 'posyandu', 'pemeriksaans'])->latest('id');
        $this->applyPendudukFilters($query, $request);
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data remaja.');
    }

    public function remajaShow($id): JsonResponse
    {
        $item = Remaja::with(['penduduk', 'posyandu', 'pemeriksaans'])->find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Data remaja tidak ditemukan.');
    }

    public function pemeriksaanRemajas(Request $request): JsonResponse
    {
        $query = PemeriksaanRemaja::with(['remaja.penduduk'])->latest('id');
        if ($request->filled('remaja_id')) {
            $query->where('remaja_id', $request->remaja_id);
        }
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data pemeriksaan remaja.');
    }

    public function lansias(Request $request): JsonResponse
    {
        $query = Lansia::with(['penduduk', 'posyandu', 'pemeriksaans'])->latest('id');
        $this->applyPendudukFilters($query, $request);
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data lansia.');
    }

    public function lansiaShow($id): JsonResponse
    {
        $item = Lansia::with(['penduduk', 'posyandu', 'pemeriksaans'])->find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Data lansia tidak ditemukan.');
    }

    public function pemeriksaanLansias(Request $request): JsonResponse
    {
        $query = PemeriksaanLansia::with(['lansia.penduduk'])->latest('id');
        if ($request->filled('lansia_id')) {
            $query->where('lansia_id', $request->lansia_id);
        }
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data pemeriksaan lansia.');
    }

    public function wuses(Request $request): JsonResponse
    {
        $query = Wus::with(['penduduk', 'posyandu'])->latest('id');
        $this->applyPendudukFilters($query, $request);
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data WUS (Wanita Usia Subur).');
    }

    public function wusShow($id): JsonResponse
    {
        $item = Wus::with(['penduduk', 'posyandu'])->find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Data WUS tidak ditemukan.');
    }

    public function puses(Request $request): JsonResponse
    {
        $query = Pus::with(['istri', 'suami', 'posyandu'])->latest('id');
        $this->applyPendudukFilters($query, $request, 'istri');
        return $this->paginateOrAll($query, $request, 'Berhasil mengambil data PUS (Pasangan Usia Subur).');
    }

    public function pusShow($id): JsonResponse
    {
        $item = Pus::with(['istri', 'suami', 'posyandu'])->find($id);
        return $item ? $this->successResponse($item) : $this->errorResponse('Data PUS tidak ditemukan.');
    }
}

