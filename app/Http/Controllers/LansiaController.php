<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\Pengaturan;
use App\Traits\HasHierarchicalFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LansiaExport;

class LansiaController extends Controller
{
    use HasHierarchicalFilter;

    public function index(Request $request)
    {
        $minAge = Pengaturan::where('key', 'umur_lansia_min')->value('value') ?? 60;

        $query = Penduduk::query()
            ->select('*')
            ->selectRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) AS umur")
            ->whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) >= ?", [$minAge]);
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%');
            });
        }

        $query = $this->applyHierarchicalFilters($query, $request);

        $sortField = $request->get('sort', 'nama');
        $sortDirection = $request->get('direction', 'asc');
        $perPage = $request->get('per_page', 10);

        $lansias = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();
        
        $filters = $this->getHierarchicalFilterOptions($request);
        return view('lansias.index', array_merge(compact('lansias', 'minAge'), $filters));
    }

    public function export(Request $request)
    {
        return Excel::download(new LansiaExport($request->all()), 'lansias.xlsx');
    }

    public function pemeriksaan(Request $request, $id)
    {
        $lansia = \App\Models\Lansia::where('id', $id)->orWhere('penduduk_id', $id)->first();
        if (!$lansia) {
            $penduduk = Penduduk::findOrFail($id);
            $lansia = \App\Models\Lansia::create([
                'penduduk_id' => $penduduk->id,
                'posyandu_id' => $penduduk->posyandu_id,
            ]);
        }

        $query = $lansia->pemeriksaans();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tanggal_pemeriksaan', 'like', "%{$search}%")
                  ->orWhere('status_tensi', 'like', "%{$search}%")
                  ->orWhere('status_gula_darah', 'like', "%{$search}%")
                  ->orWhere('keluhan', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'tanggal_pemeriksaan');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = (int) $request->get('per_page', 5);

        $allowedSorts = ['tanggal_pemeriksaan', 'berat_badan', 'tensi_sistolik', 'gula_darah', 'kolesterol', 'asam_urat'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('tanggal_pemeriksaan', 'desc');
        }

        $pemeriksaanHistory = $query->paginate($perPage)->withQueryString();

        return view('lansias.pemeriksaan', compact('lansia', 'pemeriksaanHistory'));
    }

    public function storePemeriksaan(Request $request, $id)
    {
        $lansia = \App\Models\Lansia::where('id', $id)->orWhere('penduduk_id', $id)->first();
        if (!$lansia) {
            $penduduk = Penduduk::findOrFail($id);
            $lansia = \App\Models\Lansia::create([
                'penduduk_id' => $penduduk->id,
                'posyandu_id' => $penduduk->posyandu_id,
            ]);
        }

        $request->validate([
            'tanggal_pemeriksaan' => 'required|date',
            'berat_badan' => 'nullable|numeric|min:0|max:300',
            'tinggi_badan' => 'nullable|numeric|min:0|max:250',
            'lingkar_perut' => 'nullable|numeric|min:0|max:250',
            'tensi_sistolik' => 'nullable|integer|min:40|max:300',
            'tensi_diastolik' => 'nullable|integer|min:30|max:200',
            'gula_darah' => 'nullable|integer|min:0|max:1000',
            'jenis_gula_darah' => 'nullable|in:sewaktu,puasa',
            'kolesterol' => 'nullable|integer|min:0|max:1000',
            'asam_urat' => 'nullable|numeric|min:0|max:50',
            'keluhan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $kelamin = $lansia->penduduk->kelamin ?? 'laki-laki';
        $eval = $this->hitungStatusKesehatanLansia(
            $request->berat_badan,
            $request->tinggi_badan,
            $request->tensi_sistolik,
            $request->tensi_diastolik,
            $request->gula_darah,
            $request->jenis_gula_darah ?? 'sewaktu',
            $request->kolesterol,
            $request->asam_urat,
            $kelamin
        );

        \App\Models\PemeriksaanLansia::create([
            'lansia_id' => $lansia->id,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'lingkar_perut' => $request->lingkar_perut,
            'tensi_sistolik' => $request->tensi_sistolik,
            'tensi_diastolik' => $request->tensi_diastolik,
            'gula_darah' => $request->gula_darah,
            'jenis_gula_darah' => $request->jenis_gula_darah ?? 'sewaktu',
            'kolesterol' => $request->kolesterol,
            'asam_urat' => $request->asam_urat,
            'imt' => $eval['imt'],
            'status_imt' => $eval['status_imt'],
            'status_tensi' => $eval['status_tensi'],
            'status_gula_darah' => $eval['status_gula_darah'],
            'status_kolesterol' => $eval['status_kolesterol'],
            'status_asam_urat' => $eval['status_asam_urat'],
            'keluhan' => $request->keluhan,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('lansias.pemeriksaan', $lansia->id)->with('success', 'Hasil pemeriksaan kesehatan lansia berhasil disimpan');
    }

    public function updatePemeriksaanRecord(Request $request, \App\Models\PemeriksaanLansia $pemeriksaan)
    {
        $request->validate([
            'tanggal_pemeriksaan' => 'required|date',
            'berat_badan' => 'nullable|numeric|min:0|max:300',
            'tinggi_badan' => 'nullable|numeric|min:0|max:250',
            'lingkar_perut' => 'nullable|numeric|min:0|max:250',
            'tensi_sistolik' => 'nullable|integer|min:40|max:300',
            'tensi_diastolik' => 'nullable|integer|min:30|max:200',
            'gula_darah' => 'nullable|integer|min:0|max:1000',
            'jenis_gula_darah' => 'nullable|in:sewaktu,puasa',
            'kolesterol' => 'nullable|integer|min:0|max:1000',
            'asam_urat' => 'nullable|numeric|min:0|max:50',
            'keluhan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $lansia = $pemeriksaan->lansia;
        $kelamin = $lansia->penduduk->kelamin ?? 'laki-laki';

        $eval = $this->hitungStatusKesehatanLansia(
            $request->berat_badan,
            $request->tinggi_badan,
            $request->tensi_sistolik,
            $request->tensi_diastolik,
            $request->gula_darah,
            $request->jenis_gula_darah ?? 'sewaktu',
            $request->kolesterol,
            $request->asam_urat,
            $kelamin
        );

        $pemeriksaan->update([
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'lingkar_perut' => $request->lingkar_perut,
            'tensi_sistolik' => $request->tensi_sistolik,
            'tensi_diastolik' => $request->tensi_diastolik,
            'gula_darah' => $request->gula_darah,
            'jenis_gula_darah' => $request->jenis_gula_darah ?? 'sewaktu',
            'kolesterol' => $request->kolesterol,
            'asam_urat' => $request->asam_urat,
            'imt' => $eval['imt'],
            'status_imt' => $eval['status_imt'],
            'status_tensi' => $eval['status_tensi'],
            'status_gula_darah' => $eval['status_gula_darah'],
            'status_kolesterol' => $eval['status_kolesterol'],
            'status_asam_urat' => $eval['status_asam_urat'],
            'keluhan' => $request->keluhan,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('lansias.pemeriksaan', $lansia->id)->with('success', 'Riwayat pemeriksaan lansia berhasil diperbarui');
    }

    public function destroyPemeriksaan(\App\Models\PemeriksaanLansia $pemeriksaan)
    {
        $pemeriksaan->delete();
        return back()->with('success', 'Riwayat pemeriksaan lansia berhasil dihapus');
    }

    private function hitungStatusKesehatanLansia($bb, $tb, $sistolik, $diastolik, $gula, $jenisGula, $kolesterol, $asamUrat, $kelamin)
    {
        $status = [
            'imt' => null,
            'status_imt' => null,
            'status_tensi' => null,
            'status_gula_darah' => null,
            'status_kolesterol' => null,
            'status_asam_urat' => null,
        ];

        // 1. IMT
        if ($bb && $tb && $tb > 0) {
            $tbMeter = $tb / 100;
            $imt = $bb / ($tbMeter * $tbMeter);
            $status['imt'] = round($imt, 2);

            if ($imt < 17.0) {
                $status['status_imt'] = 'Sangat Kurus';
            } elseif ($imt <= 18.4) {
                $status['status_imt'] = 'Kurus';
            } elseif ($imt <= 25.0) {
                $status['status_imt'] = 'Normal';
            } elseif ($imt <= 27.0) {
                $status['status_imt'] = 'Gemuk';
            } else {
                $status['status_imt'] = 'Obesitas';
            }
        }

        // 2. Tekanan Darah
        if ($sistolik || $diastolik) {
            $sis = $sistolik ?? 0;
            $dia = $diastolik ?? 0;

            if ($sis >= 160 || $dia >= 100) {
                $status['status_tensi'] = 'Hipertensi D2';
            } elseif ($sis >= 140 || $dia >= 90) {
                $status['status_tensi'] = 'Hipertensi D1';
            } elseif ($sis >= 120 || $dia >= 80) {
                $status['status_tensi'] = 'Prehipertensi';
            } else {
                $status['status_tensi'] = 'Normal';
            }
        }

        // 3. Gula Darah
        if ($gula) {
            if ($jenisGula === 'puasa') {
                if ($gula >= 126) {
                    $status['status_gula_darah'] = 'Diabetes';
                } elseif ($gula >= 100) {
                    $status['status_gula_darah'] = 'Pre-Diabetes';
                } else {
                    $status['status_gula_darah'] = 'Normal';
                }
            } else {
                if ($gula >= 200) {
                    $status['status_gula_darah'] = 'Diabetes';
                } elseif ($gula >= 140) {
                    $status['status_gula_darah'] = 'Tinggi';
                } else {
                    $status['status_gula_darah'] = 'Normal';
                }
            }
        }

        // 4. Kolesterol
        if ($kolesterol) {
            if ($kolesterol >= 240) {
                $status['status_kolesterol'] = 'Tinggi';
            } elseif ($kolesterol >= 200) {
                $status['status_kolesterol'] = 'Batas Tinggi';
            } else {
                $status['status_kolesterol'] = 'Normal';
            }
        }

        // 5. Asam Urat
        if ($asamUrat) {
            $jk = strtolower($kelamin ?? 'laki-laki');
            $limit = ($jk === 'laki-laki' || $jk === 'l') ? 7.0 : 6.0;

            if ($asamUrat > $limit) {
                $status['status_asam_urat'] = 'Tinggi';
            } else {
                $status['status_asam_urat'] = 'Normal';
            }
        }

        return $status;
    }
}
