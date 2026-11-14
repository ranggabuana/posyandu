<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use App\Models\ApiLog;
use App\Services\ApiSettingsService;
use Illuminate\Http\Request;

class ApiManagementController extends Controller
{
    private function authorizeAdmin(): void
    {
        if (auth()->check() && auth()->user()->hasRole('posyandu')) {
            abort(403, 'Anda tidak memiliki akses ke halaman Manajemen API.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $settings = ApiSettingsService::all();
        $apiKeys = ApiKey::with('user')->latest()->get();
        $logs = ApiLog::with('apiKey')->latest('created_at')->paginate(15)->withQueryString();

        $stats = [
            'active_keys' => $apiKeys->where('is_active', true)->count(),
            'total_keys' => $apiKeys->count(),
            'requests_today' => ApiLog::whereDate('created_at', today())->count(),
            'errors_today' => ApiLog::whereDate('created_at', today())->where('status_code', '>=', 400)->count(),
        ];

        $abilities = ApiKey::AVAILABLE_ABILITIES;

        return view('admin.api.index', compact('settings', 'apiKeys', 'logs', 'stats', 'abilities'));
    }

    public function updateSettings(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'api_rate_limit' => 'required|integer|min:1|max:10000',
            'api_ip_whitelist' => 'nullable|string|max:2000',
            'api_cors_origins' => 'required|string|max:1000',
            'api_default_per_page' => 'required|integer|min:1|max:500',
            'api_max_per_page' => 'required|integer|min:1|max:1000',
        ]);

        ApiSettingsService::updateMany([
            'api_enabled' => $request->boolean('api_enabled') ? '1' : '0',
            'api_auth_required' => $request->boolean('api_auth_required') ? '1' : '0',
            'api_force_https' => $request->boolean('api_force_https') ? '1' : '0',
            'api_logging_enabled' => $request->boolean('api_logging_enabled') ? '1' : '0',
            'api_mask_sensitive' => $request->boolean('api_mask_sensitive') ? '1' : '0',
            'api_rate_limit' => $validated['api_rate_limit'],
            'api_ip_whitelist' => $validated['api_ip_whitelist'] ?? '',
            'api_cors_origins' => $validated['api_cors_origins'],
            'api_default_per_page' => $validated['api_default_per_page'],
            'api_max_per_page' => $validated['api_max_per_page'],
        ]);

        return redirect()->route('admin.api.index', ['tab' => 'settings'])
            ->with('success', 'Pengaturan & keamanan REST API berhasil disimpan.');
    }

    public function storeKey(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'abilities' => 'required|array|min:1',
            'abilities.*' => 'string',
            'allowed_ips' => 'nullable|string|max:1000',
            'rate_limit' => 'nullable|integer|min:1|max:10000',
            'expires_at' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:500',
        ]);

        $generated = ApiKey::generateKey();

        ApiKey::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'key_prefix' => $generated['prefix'],
            'key_hash' => $generated['hash'],
            'abilities' => $validated['abilities'],
            'allowed_ips' => $validated['allowed_ips'] ?? null,
            'rate_limit' => $validated['rate_limit'] ?? null,
            'is_active' => true,
            'expires_at' => $validated['expires_at'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.api.index', ['tab' => 'keys'])
            ->with('success', 'API Key baru berhasil dibuat. Segera salin token di bawah karena hanya ditampilkan satu kali.')
            ->with('new_api_key', $generated['plain']);
    }

    public function toggleKey(ApiKey $apiKey)
    {
        $this->authorizeAdmin();

        $apiKey->update(['is_active' => !$apiKey->is_active]);

        return redirect()->route('admin.api.index', ['tab' => 'keys'])
            ->with('success', 'Status API Key "' . $apiKey->name . '" berhasil diperbarui.');
    }

    public function destroyKey(ApiKey $apiKey)
    {
        $this->authorizeAdmin();

        $name = $apiKey->name;
        $apiKey->delete();

        return redirect()->route('admin.api.index', ['tab' => 'keys'])
            ->with('success', 'API Key "' . $name . '" berhasil dihapus.');
    }

    public function clearLogs()
    {
        $this->authorizeAdmin();

        ApiLog::truncate();

        return redirect()->route('admin.api.index', ['tab' => 'logs'])
            ->with('success', 'Seluruh log aktivitas API berhasil dibersihkan.');
    }

    public function docs()
    {
        $this->authorizeAdmin();

        $settings = ApiSettingsService::all();
        $baseUrl = url('/api/v1');
        $endpointGroups = $this->getEndpointCatalog();

        return view('admin.api.docs', compact('settings', 'baseUrl', 'endpointGroups'));
    }

    private function getEndpointCatalog(): array
    {
        return [
            [
                'group' => 'Umum & Statistik',
                'icon' => 'mdi-chart-box-outline',
                'color' => 'blue',
                'ability' => 'read:statistik',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/health', 'desc' => 'Cek status server & konfigurasi dasar REST API (Publik)', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/statistik', 'desc' => 'Ringkasan statistik seluruh data kependudukan, kesehatan, kelembagaan & konten', 'params' => '-'],
                ],
            ],
            [
                'group' => 'Kependudukan',
                'icon' => 'mdi-account-group-outline',
                'color' => 'indigo',
                'ability' => 'read:penduduk',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/penduduks', 'desc' => 'Daftar seluruh penduduk desa', 'params' => 'search, dusun, rw, rt, kelamin, sort, direction, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/penduduks/{id}', 'desc' => 'Detail data satu penduduk berdasarkan ID', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/ibus', 'desc' => 'Daftar data Ibu', 'params' => 'search, dusun, rw, rt, per_page, page, all=1'],
                ],
            ],
            [
                'group' => 'Data Kesehatan (Siklus Hidup)',
                'icon' => 'mdi-medical-bag',
                'color' => 'emerald',
                'ability' => 'read:kesehatan',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/ibu-hamils', 'desc' => 'Daftar data Ibu Hamil beserta relasi penduduk, suami & posyandu', 'params' => 'search, posyandu_id, dusun, rw, rt, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/ibu-hamils/{id}', 'desc' => 'Detail data Ibu Hamil', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/bayi', 'desc' => 'Daftar data Bayi (usia 0–12 bulan) beserta riwayat pemeriksaan & imunisasi', 'params' => 'search, posyandu_id, dusun, rw, rt, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/bayi/{id}', 'desc' => 'Detail data Bayi', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/balita', 'desc' => 'Daftar data Balita (usia 13–60 bulan) beserta riwayat pemeriksaan & imunisasi', 'params' => 'search, posyandu_id, dusun, rw, rt, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/balita/{id}', 'desc' => 'Detail data Balita', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/bayi-balitas', 'desc' => 'Gabungan seluruh data Bayi & Balita', 'params' => 'search, posyandu_id, dusun, rw, rt, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/bayi-balitas/{id}', 'desc' => 'Detail data Bayi/Balita', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/pemeriksaan-balitas', 'desc' => 'Riwayat pemeriksaan antropometri Bayi & Balita', 'params' => 'bayi_balita_id, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/imunisasi-balitas', 'desc' => 'Riwayat imunisasi Bayi & Balita', 'params' => 'bayi_balita_id, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/remajas', 'desc' => 'Daftar data Remaja beserta riwayat pemeriksaan', 'params' => 'search, posyandu_id, dusun, rw, rt, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/remajas/{id}', 'desc' => 'Detail data Remaja', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/pemeriksaan-remajas', 'desc' => 'Riwayat pemeriksaan Remaja', 'params' => 'remaja_id, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/lansias', 'desc' => 'Daftar data Lansia beserta riwayat pemeriksaan', 'params' => 'search, posyandu_id, dusun, rw, rt, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/lansias/{id}', 'desc' => 'Detail data Lansia', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/pemeriksaan-lansias', 'desc' => 'Riwayat pemeriksaan Lansia', 'params' => 'lansia_id, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/wuses', 'desc' => 'Daftar data Wanita Usia Subur (WUS)', 'params' => 'search, posyandu_id, dusun, rw, rt, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/wuses/{id}', 'desc' => 'Detail data WUS', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/puses', 'desc' => 'Daftar data Pasangan Usia Subur (PUS)', 'params' => 'search, posyandu_id, dusun, rw, rt, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/puses/{id}', 'desc' => 'Detail data PUS', 'params' => '-'],
                ],
            ],
            [
                'group' => 'Konten Publik',
                'icon' => 'mdi-newspaper-variant-outline',
                'color' => 'purple',
                'ability' => 'read:konten',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/beritas', 'desc' => 'Daftar berita & artikel posyandu', 'params' => 'search, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/beritas/{id}', 'desc' => 'Detail berita', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/galeries', 'desc' => 'Daftar foto galeri kegiatan', 'params' => 'search, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/galeries/{id}', 'desc' => 'Detail galeri', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/jadwals', 'desc' => 'Jadwal pelayanan posyandu', 'params' => 'search, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/jadwals/{id}', 'desc' => 'Detail jadwal pelayanan', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/tims', 'desc' => 'Daftar anggota tim posyandu', 'params' => 'search, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/tims/{id}', 'desc' => 'Detail anggota tim', 'params' => '-'],
                ],
            ],
            [
                'group' => 'Interaksi Masyarakat',
                'icon' => 'mdi-message-processing-outline',
                'color' => 'amber',
                'ability' => 'read:interaksi',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/buku-tamus', 'desc' => 'Daftar catatan buku tamu', 'params' => 'search, posyandu_id, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/buku-tamus/{id}', 'desc' => 'Detail buku tamu', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/laporan-masyarakats', 'desc' => 'Daftar laporan / aduan masyarakat', 'params' => 'search, status, posyandu_id, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/laporan-masyarakats/{id}', 'desc' => 'Detail laporan masyarakat', 'params' => '-'],
                ],
            ],
            [
                'group' => 'Data Master & Pengaturan',
                'icon' => 'mdi-cog-outline',
                'color' => 'slate',
                'ability' => 'read:master',
                'endpoints' => [
                    ['method' => 'GET', 'path' => '/posyandus', 'desc' => 'Daftar posyandu beserta daftar kadernya', 'params' => 'search, dusun, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/posyandus/{id}', 'desc' => 'Detail posyandu', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/kaders', 'desc' => 'Daftar kader posyandu', 'params' => 'search, posyandu_id, status_aktif, per_page, page, all=1'],
                    ['method' => 'GET', 'path' => '/kaders/{id}', 'desc' => 'Detail kader', 'params' => '-'],
                    ['method' => 'GET', 'path' => '/pengaturans', 'desc' => 'Pengaturan umum aplikasi (tidak termasuk konfigurasi rahasia API)', 'params' => 'per_page, page, all=1'],
                ],
            ],
        ];
    }
}

