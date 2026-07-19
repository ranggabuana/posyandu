<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\Pengaturan;
use App\Models\Remaja;
use App\Models\PemeriksaanRemaja;
use App\Traits\HasHierarchicalFilter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RemajaExport;

class RemajaController extends Controller
{
    use HasHierarchicalFilter;

    public function index(Request $request)
    {
        $minAge = Pengaturan::where('key', 'remaja_umur_min')->value('value') ?? 10;
        $maxAge = Pengaturan::where('key', 'remaja_umur_max')->value('value') ?? 18;

        $query = Penduduk::query()
            ->select('*')
            ->selectRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) AS umur")
            ->whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN ? AND ?", [$minAge, $maxAge]);
        
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

        $remajas = $query->orderBy($sortField, $sortDirection)->paginate($perPage)->withQueryString();
        
        $filters = $this->getHierarchicalFilterOptions($request);
        return view('remajas.index', array_merge(compact('remajas', 'minAge', 'maxAge'), $filters));
    }

    public function export(Request $request)
    {
        return Excel::download(new RemajaExport($request->all()), 'remajas.xlsx');
    }

    public function pemeriksaan(Request $request, $id)
    {
        $remaja = Remaja::with(['penduduk', 'posyandu'])->where('id', $id)->orWhere('penduduk_id', $id)->first();

        if (!$remaja) {
            $penduduk = Penduduk::findOrFail($id);
            $remaja = Remaja::create([
                'penduduk_id' => $penduduk->id,
                'posyandu_id' => $penduduk->posyandu_id,
            ]);
            $remaja->load(['penduduk', 'posyandu']);
        }

        $query = $remaja->pemeriksaans();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('catatan', 'like', "%{$search}%")
                  ->orWhere('keluhan', 'like', "%{$search}%")
                  ->orWhere('status_imt', 'like', "%{$search}%")
                  ->orWhere('status_hb', 'like', "%{$search}%")
                  ->orWhere('status_lila', 'like', "%{$search}%")
                  ->orWhere('status_tensi', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'tanggal_pemeriksaan');
        $sortDirection = $request->get('direction', 'desc');
        $perPage = (int) $request->get('per_page', 5);

        $allowedSorts = ['tanggal_pemeriksaan', 'berat_badan', 'tinggi_badan', 'hemoglobin', 'lila', 'tensi_sistolik', 'gula_darah'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('tanggal_pemeriksaan', 'desc');
        }

        $pemeriksaanHistory = $query->paginate($perPage)->withQueryString();

        return view('remajas.pemeriksaan', compact('remaja', 'pemeriksaanHistory'));
    }

    public function storePemeriksaan(Request $request, $id)
    {
        $remaja = Remaja::where('id', $id)->orWhere('penduduk_id', $id)->first();
        if (!$remaja) {
            $penduduk = Penduduk::findOrFail($id);
            $remaja = Remaja::create([
                'penduduk_id' => $penduduk->id,
                'posyandu_id' => $penduduk->posyandu_id,
            ]);
        }

        $request->validate([
            'tanggal_pemeriksaan' => 'required|date',
            'berat_badan' => 'nullable|numeric|min:10|max:200',
            'tinggi_badan' => 'nullable|numeric|min:50|max:250',
            'lila' => 'nullable|numeric|min:5|max:60',
            'hemoglobin' => 'nullable|numeric|min:2|max:25',
            'tensi_sistolik' => 'nullable|integer|min:40|max:250',
            'tensi_diastolik' => 'nullable|integer|min:20|max:150',
            'gula_darah' => 'nullable|integer|min:20|max:600',
            'jenis_gula_darah' => 'required|in:sewaktu,puasa',
            'lingkar_perut' => 'nullable|numeric|min:20|max:200',
            'pemberian_ttd' => 'required|in:ya,tidak',
            'keluhan' => 'nullable|string|max:1000',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $calculated = $this->hitungStatusKesehatanRemaja($request->all());

        PemeriksaanRemaja::create([
            'remaja_id' => $remaja->id,
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'lila' => $request->lila,
            'status_lila' => $calculated['status_lila'],
            'hemoglobin' => $request->hemoglobin,
            'status_hb' => $calculated['status_hb'],
            'lingkar_perut' => $request->lingkar_perut,
            'tensi_sistolik' => $request->tensi_sistolik,
            'tensi_diastolik' => $request->tensi_diastolik,
            'gula_darah' => $request->gula_darah,
            'jenis_gula_darah' => $request->jenis_gula_darah,
            'imt' => $calculated['imt'],
            'status_imt' => $calculated['status_imt'],
            'status_tensi' => $calculated['status_tensi'],
            'status_gula_darah' => $calculated['status_gula_darah'],
            'pemberian_ttd' => $request->pemberian_ttd,
            'keluhan' => $request->keluhan,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('remajas.pemeriksaan', $remaja->id)
            ->with('success', 'Data pemeriksaan kesehatan remaja berhasil disimpan.');
    }

    public function updatePemeriksaanRecord(Request $request, $pemeriksaanId)
    {
        $pemeriksaan = PemeriksaanRemaja::findOrFail($pemeriksaanId);

        $request->validate([
            'tanggal_pemeriksaan' => 'required|date',
            'berat_badan' => 'nullable|numeric|min:10|max:200',
            'tinggi_badan' => 'nullable|numeric|min:50|max:250',
            'lila' => 'nullable|numeric|min:5|max:60',
            'hemoglobin' => 'nullable|numeric|min:2|max:25',
            'tensi_sistolik' => 'nullable|integer|min:40|max:250',
            'tensi_diastolik' => 'nullable|integer|min:20|max:150',
            'gula_darah' => 'nullable|integer|min:20|max:600',
            'jenis_gula_darah' => 'required|in:sewaktu,puasa',
            'lingkar_perut' => 'nullable|numeric|min:20|max:200',
            'pemberian_ttd' => 'required|in:ya,tidak',
            'keluhan' => 'nullable|string|max:1000',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $calculated = $this->hitungStatusKesehatanRemaja($request->all());

        $pemeriksaan->update([
            'tanggal_pemeriksaan' => $request->tanggal_pemeriksaan,
            'berat_badan' => $request->berat_badan,
            'tinggi_badan' => $request->tinggi_badan,
            'lila' => $request->lila,
            'status_lila' => $calculated['status_lila'],
            'hemoglobin' => $request->hemoglobin,
            'status_hb' => $calculated['status_hb'],
            'lingkar_perut' => $request->lingkar_perut,
            'tensi_sistolik' => $request->tensi_sistolik,
            'tensi_diastolik' => $request->tensi_diastolik,
            'gula_darah' => $request->gula_darah,
            'jenis_gula_darah' => $request->jenis_gula_darah,
            'imt' => $calculated['imt'],
            'status_imt' => $calculated['status_imt'],
            'status_tensi' => $calculated['status_tensi'],
            'status_gula_darah' => $calculated['status_gula_darah'],
            'pemberian_ttd' => $request->pemberian_ttd,
            'keluhan' => $request->keluhan,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('remajas.pemeriksaan', $pemeriksaan->remaja_id)
            ->with('success', 'Rekam pemeriksaan kesehatan remaja berhasil diperbarui.');
    }

    public function destroyPemeriksaan($pemeriksaanId)
    {
        $pemeriksaan = PemeriksaanRemaja::findOrFail($pemeriksaanId);
        $remajaId = $pemeriksaan->remaja_id;
        $pemeriksaan->delete();

        return redirect()->route('remajas.pemeriksaan', $remajaId)
            ->with('success', 'Data riwayat pemeriksaan remaja berhasil dihapus.');
    }

    private function hitungStatusKesehatanRemaja(array $data)
    {
        $bb = floatval($data['berat_badan'] ?? 0);
        $tb = floatval($data['tinggi_badan'] ?? 0);
        $lila = floatval($data['lila'] ?? 0);
        $hb = floatval($data['hemoglobin'] ?? 0);
        $sistolik = intval($data['tensi_sistolik'] ?? 0);
        $diastolik = intval($data['tensi_diastolik'] ?? 0);
        $gula = intval($data['gula_darah'] ?? 0);
        $jenisGula = $data['jenis_gula_darah'] ?? 'sewaktu';

        // IMT & Status Gizi Remaja (Kemenkes RI)
        $imt = null;
        $statusImt = null;
        if ($bb > 0 && $tb > 0) {
            $tbMeter = $tb / 100;
            $imt = round($bb / ($tbMeter * $tbMeter), 2);
            if ($imt < 17.0) {
                $statusImt = 'Sangat Kurus';
            } elseif ($imt >= 17.0 && $imt <= 18.4) {
                $statusImt = 'Kurus';
            } elseif ($imt >= 18.5 && $imt <= 25.0) {
                $statusImt = 'Normal';
            } elseif ($imt >= 25.1 && $imt <= 27.0) {
                $statusImt = 'Gemuk';
            } else {
                $statusImt = 'Obesitas';
            }
        }

        // Status LiLA (Skrining KEK / Kurang Energi Kronis)
        $statusLila = null;
        if ($lila > 0) {
            if ($lila < 23.5) {
                $statusLila = 'Resiko KEK';
            } else {
                $statusLila = 'Normal';
            }
        }

        // Status Hemoglobin / Skrining Anemia Kemenkes RI
        $statusHb = null;
        if ($hb > 0) {
            if ($hb < 8.0) {
                $statusHb = 'Anemia Berat';
            } elseif ($hb >= 8.0 && $hb <= 10.9) {
                $statusHb = 'Anemia Sedang';
            } elseif ($hb >= 11.0 && $hb <= 11.9) {
                $statusHb = 'Anemia Ringan';
            } else {
                $statusHb = 'Normal';
            }
        }

        // Status Tensi Remaja
        $statusTensi = null;
        if ($sistolik > 0 || $diastolik > 0) {
            if ($sistolik >= 140 || $diastolik >= 90) {
                $statusTensi = 'Hipertensi';
            } elseif ($sistolik >= 120 || $diastolik >= 80) {
                $statusTensi = 'Pre-Hipertensi';
            } else {
                $statusTensi = 'Normal';
            }
        }

        // Status Gula Darah
        $statusGula = null;
        if ($gula > 0) {
            if ($jenisGula === 'puasa') {
                if ($gula >= 126) {
                    $statusGula = 'Diabetes';
                } elseif ($gula >= 100) {
                    $statusGula = 'Pre-Diabetes';
                } else {
                    $statusGula = 'Normal';
                }
            } else {
                if ($gula >= 200) {
                    $statusGula = 'Tinggi';
                } elseif ($gula >= 140) {
                    $statusGula = 'Batas Tinggi';
                } else {
                    $statusGula = 'Normal';
                }
            }
        }

        return [
            'imt' => $imt,
            'status_imt' => $statusImt,
            'status_lila' => $statusLila,
            'status_hb' => $statusHb,
            'status_tensi' => $statusTensi,
            'status_gula_darah' => $statusGula,
        ];
    }
}
