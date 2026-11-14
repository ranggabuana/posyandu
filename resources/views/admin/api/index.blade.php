<x-layout title="Manajemen REST API">
    <x-page-header
        title="Manajemen REST API"
        subtitle="Pengaturan Keamanan, API Key / Token, dan Log Aktivitas REST API SIP Posyandu"
        icon="mdi-api"
        :breadcrumbs="[['label' => 'Manajemen API']]"
    >
        <a href="{{ route('admin.api.docs') }}"
            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-xs font-bold transition shadow-xs">
            <i class="mdi mdi-book-open-page-variant text-sm"></i>
            <span>Dokumentasi & Uji API</span>
        </a>
    </x-page-header>

    @if(session('success'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-2xl flex items-center gap-3 shadow-2xs">
            <i class="mdi mdi-check-circle text-xl text-emerald-600"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('new_api_key'))
        <div class="mb-6 bg-amber-50 border-2 border-amber-300 rounded-2xl p-5 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="p-2.5 bg-amber-500 text-white rounded-xl shrink-0">
                    <i class="mdi mdi-key-alert text-xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-bold text-amber-900 text-sm">API Key Baru Berhasil Dibuat!</h4>
                    <p class="text-xs text-amber-800 mt-1">
                        Simpan token ini di tempat yang aman. Demi keamanan, token lengkap <strong>tidak akan ditampilkan lagi</strong> setelah halaman ini dimuat ulang.
                    </p>
                    <div class="mt-3 flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <input type="text" id="newApiKeyValue" readonly value="{{ session('new_api_key') }}"
                            class="flex-1 bg-white border border-amber-300 rounded-xl px-3.5 py-2 text-xs font-mono text-gray-900 select-all">
                        <button type="button" onclick="copyText('newApiKeyValue', this)"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-bold transition cursor-pointer">
                            <i class="mdi mdi-content-copy"></i>
                            <span>Salin Token</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-800 px-4 py-3.5 rounded-2xl text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Ringkasan Status -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500">Status REST API</span>
                <span class="p-2 rounded-xl {{ $settings['api_enabled'] === '1' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                    <i class="mdi {{ $settings['api_enabled'] === '1' ? 'mdi-check-network' : 'mdi-close-network' }} text-lg"></i>
                </span>
            </div>
            <div class="mt-2 flex items-center gap-2">
                <span class="text-lg font-bold {{ $settings['api_enabled'] === '1' ? 'text-emerald-700' : 'text-red-700' }}">
                    {{ $settings['api_enabled'] === '1' ? 'Aktif' : 'Nonaktif' }}
                </span>
                <span class="text-[11px] px-2 py-0.5 rounded-full {{ $settings['api_auth_required'] === '1' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }}">
                    {{ $settings['api_auth_required'] === '1' ? 'Wajib Auth' : 'Publik' }}
                </span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500">API Key Aktif</span>
                <span class="p-2 rounded-xl bg-blue-50 text-blue-600">
                    <i class="mdi mdi-key-chain text-lg"></i>
                </span>
            </div>
            <div class="mt-2 text-lg font-bold text-gray-900">
                {{ $stats['active_keys'] }} <span class="text-xs font-normal text-gray-400">/ {{ $stats['total_keys'] }} total</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500">Request Hari Ini</span>
                <span class="p-2 rounded-xl bg-indigo-50 text-indigo-600">
                    <i class="mdi mdi-swap-horizontal text-lg"></i>
                </span>
            </div>
            <div class="mt-2 text-lg font-bold text-gray-900">
                {{ number_format($stats['requests_today']) }}
                @if($stats['errors_today'] > 0)
                    <span class="text-xs font-semibold text-red-600 ml-1">({{ $stats['errors_today'] }} gagal)</span>
                @endif
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500">Rate Limit Global</span>
                <span class="p-2 rounded-xl bg-purple-50 text-purple-600">
                    <i class="mdi mdi-speedometer text-lg"></i>
                </span>
            </div>
            <div class="mt-2 text-lg font-bold text-gray-900">
                {{ $settings['api_rate_limit'] }} <span class="text-xs font-normal text-gray-500">req / menit</span>
            </div>
        </div>
    </div>

    <!-- Navigasi Tab -->
    @php $activeTab = request('tab', session('new_api_key') ? 'keys' : 'settings'); @endphp
    <div class="bg-white rounded-2xl border border-gray-100 shadow-2xs overflow-hidden">
        <div class="border-b border-gray-200 px-6 pt-4 flex flex-wrap gap-2 bg-gray-50/50">
            <button type="button" onclick="switchTab('settings')" id="tab-btn-settings"
                class="tab-btn px-4 py-2.5 text-sm font-semibold rounded-t-xl border-b-2 transition cursor-pointer flex items-center gap-2 {{ $activeTab === 'settings' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                <i class="mdi mdi-shield-lock-outline"></i>
                <span>Pengaturan & Keamanan</span>
            </button>
            <button type="button" onclick="switchTab('keys')" id="tab-btn-keys"
                class="tab-btn px-4 py-2.5 text-sm font-semibold rounded-t-xl border-b-2 transition cursor-pointer flex items-center gap-2 {{ $activeTab === 'keys' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                <i class="mdi mdi-key-variant"></i>
                <span>API Key / Token ({{ $stats['total_keys'] }})</span>
            </button>
            <button type="button" onclick="switchTab('logs')" id="tab-btn-logs"
                class="tab-btn px-4 py-2.5 text-sm font-semibold rounded-t-xl border-b-2 transition cursor-pointer flex items-center gap-2 {{ $activeTab === 'logs' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                <i class="mdi mdi-history"></i>
                <span>Log Aktivitas API</span>
            </button>
        </div>

        <!-- TAB 1: PENGATURAN & KEAMANAN -->
        <div id="tab-panel-settings" class="tab-panel p-6 {{ $activeTab === 'settings' ? '' : 'hidden' }}">
            <form action="{{ route('admin.api.settings.update') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Toggle Switches -->
                    <label class="flex items-start justify-between p-4 rounded-xl border border-gray-200 hover:border-blue-300 transition cursor-pointer bg-gray-50/40">
                        <div class="pr-4">
                            <span class="block text-sm font-bold text-gray-800">Aktifkan REST API</span>
                            <span class="block text-xs text-gray-500 mt-0.5">Saklar utama untuk mengaktifkan atau menonaktifkan seluruh endpoint <code>/api/v1/*</code>.</span>
                        </div>
                        <input type="checkbox" name="api_enabled" value="1" {{ $settings['api_enabled'] === '1' ? 'checked' : '' }}
                            class="mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    </label>

                    <label class="flex items-start justify-between p-4 rounded-xl border border-gray-200 hover:border-blue-300 transition cursor-pointer bg-gray-50/40">
                        <div class="pr-4">
                            <span class="block text-sm font-bold text-gray-800">Wajib Autentikasi API Key</span>
                            <span class="block text-xs text-gray-500 mt-0.5">Setiap request wajib menyertakan header <code>X-API-Key</code> atau <code>Authorization: Bearer</code>.</span>
                        </div>
                        <input type="checkbox" name="api_auth_required" value="1" {{ $settings['api_auth_required'] === '1' ? 'checked' : '' }}
                            class="mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    </label>

                    <label class="flex items-start justify-between p-4 rounded-xl border border-gray-200 hover:border-blue-300 transition cursor-pointer bg-gray-50/40">
                        <div class="pr-4">
                            <span class="block text-sm font-bold text-gray-800">Samarkan Data Sensitif (Masking)</span>
                            <span class="block text-xs text-gray-500 mt-0.5">Samarkan otomatis kolom <code>nik</code>, <code>no_kk</code>, dan nomor telepon pada respons API.</span>
                        </div>
                        <input type="checkbox" name="api_mask_sensitive" value="1" {{ $settings['api_mask_sensitive'] === '1' ? 'checked' : '' }}
                            class="mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    </label>

                    <label class="flex items-start justify-between p-4 rounded-xl border border-gray-200 hover:border-blue-300 transition cursor-pointer bg-gray-50/40">
                        <div class="pr-4">
                            <span class="block text-sm font-bold text-gray-800">Catat Log Aktivitas API</span>
                            <span class="block text-xs text-gray-500 mt-0.5">Rekam IP, endpoint, status HTTP, dan waktu respons setiap request API.</span>
                        </div>
                        <input type="checkbox" name="api_logging_enabled" value="1" {{ $settings['api_logging_enabled'] === '1' ? 'checked' : '' }}
                            class="mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    </label>

                    <label class="flex items-start justify-between p-4 rounded-xl border border-gray-200 hover:border-blue-300 transition cursor-pointer bg-gray-50/40 md:col-span-2">
                        <div class="pr-4">
                            <span class="block text-sm font-bold text-gray-800">Wajib HTTPS (Mode Production)</span>
                            <span class="block text-xs text-gray-500 mt-0.5">Tolak koneksi HTTP biasa saat aplikasi berjalan di lingkungan <code>production</code>.</span>
                        </div>
                        <input type="checkbox" name="api_force_https" value="1" {{ $settings['api_force_https'] === '1' ? 'checked' : '' }}
                            class="mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    </label>
                </div>

                <!-- Numeric & Text Security Settings -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Rate Limit Global (Request / Menit)</label>
                        <input type="number" name="api_rate_limit" min="1" max="10000" value="{{ old('api_rate_limit', $settings['api_rate_limit']) }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:bg-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Default Item Per Halaman</label>
                        <input type="number" name="api_default_per_page" min="1" max="500" value="{{ old('api_default_per_page', $settings['api_default_per_page']) }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:bg-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Maksimal Item Per Halaman</label>
                        <input type="number" name="api_max_per_page" min="1" max="1000" value="{{ old('api_max_per_page', $settings['api_max_per_page']) }}"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:bg-white focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">CORS Allowed Origins</label>
                        <input type="text" name="api_cors_origins" value="{{ old('api_cors_origins', $settings['api_cors_origins']) }}"
                            placeholder="* atau https://domain1.com, https://domain2.com"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm font-mono focus:border-blue-500 focus:bg-white focus:outline-none">
                        <p class="text-[11px] text-gray-500 mt-1">Gunakan <code>*</code> untuk mengizinkan semua domain, atau pisahkan beberapa domain dengan koma.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">IP Whitelist Global (Opsional)</label>
                        <textarea name="api_ip_whitelist" rows="2" placeholder="Contoh: 127.0.0.1, 103.10.20.30 (Kosongkan untuk semua IP)"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm font-mono focus:border-blue-500 focus:bg-white focus:outline-none">{{ old('api_ip_whitelist', $settings['api_ip_whitelist']) }}</textarea>
                        <p class="text-[11px] text-gray-500 mt-1">Jika diisi, hanya IP dalam daftar ini yang dapat mengakses REST API.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-2 border-t border-gray-100">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition shadow-xs cursor-pointer">
                        <i class="mdi mdi-content-save"></i>
                        <span>Simpan Pengaturan Keamanan</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- TAB 2: API KEYS -->
        <div id="tab-panel-keys" class="tab-panel p-6 {{ $activeTab === 'keys' ? '' : 'hidden' }}">
            <div class="mb-6 bg-blue-50/50 border border-blue-100 rounded-2xl p-5">
                <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="mdi mdi-plus-circle text-blue-600 text-lg"></i>
                    <span>Buat API Key / Token Baru</span>
                </h4>
                <form action="{{ route('admin.api.keys.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Aplikasi / Klien *</label>
                            <input type="text" name="name" required placeholder="Misal: Dashboard Kecamatan / Mobile App"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Rate Limit Khusus (Opsional)</label>
                            <input type="number" name="rate_limit" min="1" max="10000" placeholder="Ikuti global ({{ $settings['api_rate_limit'] }}/mnt)"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Masa Berlaku (Opsional)</label>
                            <input type="date" name="expires_at"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Hak Akses (Scopes / Abilities) *</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                            @foreach($abilities as $key => $label)
                                <label class="flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-gray-200 text-xs cursor-pointer hover:border-blue-300">
                                    <input type="checkbox" name="abilities[]" value="{{ $key }}" {{ $key === '*' ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="font-medium text-gray-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">IP Whitelist Khusus Key Ini (Opsional)</label>
                            <input type="text" name="allowed_ips" placeholder="Contoh: 192.168.1.10, 103.20.1.5"
                                class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-sm font-mono focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Catatan (Opsional)</label>
                            <input type="text" name="notes" placeholder="Keterangan penggunaan token..."
                                class="w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition cursor-pointer">
                            <i class="mdi mdi-key-plus"></i>
                            <span>Generate API Key</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel API Keys -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-[11px] font-bold text-gray-500 uppercase bg-gray-50">
                            <th class="py-3 px-4">Nama & Prefix</th>
                            <th class="py-3 px-4">Hak Akses</th>
                            <th class="py-3 px-4">Statistik Penggunaan</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($apiKeys as $k)
                            <tr class="hover:bg-gray-50/70">
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-gray-800">{{ $k->name }}</div>
                                    <div class="text-xs font-mono text-gray-500 mt-0.5">{{ $k->key_prefix }}</div>
                                    @if($k->notes)
                                        <div class="text-[11px] text-gray-400 mt-0.5">{{ $k->notes }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($k->abilities ?? [] as $ab)
                                            <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $ab === '*' ? 'Akses Penuh' : $ab }}
                                            </span>
                                        @endforeach
                                    </div>
                                    @if($k->allowed_ips)
                                        <div class="text-[11px] text-gray-500 mt-1 font-mono">IP: {{ $k->allowed_ips }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-xs text-gray-600">
                                    <div><strong>{{ number_format($k->hit_count) }}</strong> hit</div>
                                    <div class="text-[11px] text-gray-400">
                                        Terakhir: {{ $k->last_used_at ? $k->last_used_at->diffForHumans() : 'Belum pernah' }}
                                    </div>
                                    @if($k->expires_at)
                                        <div class="text-[11px] {{ $k->isExpired() ? 'text-red-600 font-bold' : 'text-gray-400' }}">
                                            Exp: {{ $k->expires_at->format('d M Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if(!$k->is_active)
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600">Nonaktif</span>
                                    @elseif($k->isExpired())
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-50 text-red-700">Kedaluwarsa</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-700">Aktif</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <form action="{{ route('admin.api.keys.toggle', $k) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="{{ $k->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                class="px-2.5 py-1.5 rounded-lg text-xs font-semibold border cursor-pointer {{ $k->is_active ? 'border-amber-200 text-amber-700 hover:bg-amber-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}">
                                                <i class="mdi {{ $k->is_active ? 'mdi-pause' : 'mdi-play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.api.keys.destroy', $k) }}" method="POST" onsubmit="return confirm('Hapus API Key ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus"
                                                class="px-2.5 py-1.5 rounded-lg text-xs font-semibold border border-red-200 text-red-600 hover:bg-red-50 cursor-pointer">
                                                <i class="mdi mdi-delete-outline"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-sm text-gray-400">
                                    Belum ada API Key yang dibuat. Buat API Key baru di atas untuk mulai mengintegrasikan aplikasi lain.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 3: LOG AKTIVITAS -->
        <div id="tab-panel-logs" class="tab-panel p-6 {{ $activeTab === 'logs' ? '' : 'hidden' }}">
            <div class="flex items-center justify-between mb-4">
                <p class="text-xs text-gray-500">Menampilkan riwayat akses REST API terbaru.</p>
                <form action="{{ route('admin.api.logs.clear') }}" method="POST" onsubmit="return confirm('Bersihkan seluruh log aktivitas API?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded-xl text-xs font-bold transition cursor-pointer">
                        <i class="mdi mdi-broom"></i>
                        <span>Bersihkan Log</span>
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-[11px] font-bold text-gray-500 uppercase bg-gray-50">
                            <th class="py-3 px-4">Waktu</th>
                            <th class="py-3 px-4">Endpoint</th>
                            <th class="py-3 px-4">API Key / IP</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Durasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50/70">
                                <td class="py-3 px-4 text-gray-600 whitespace-nowrap">
                                    {{ $log->created_at?->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="py-3 px-4 font-mono">
                                    <span class="px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 font-bold mr-1">{{ $log->method }}</span>
                                    <span class="text-gray-800">{{ $log->endpoint }}</span>
                                    @if($log->error_message)
                                        <div class="text-[11px] text-red-600 mt-0.5 font-sans">{{ $log->error_message }}</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-semibold text-gray-700">{{ $log->apiKey?->name ?? 'Publik / Tanpa Key' }}</div>
                                    <div class="text-[11px] font-mono text-gray-400">{{ $log->ip_address }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    @php
                                        $badge = $log->status_code < 300 ? 'bg-emerald-50 text-emerald-700' : ($log->status_code < 500 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700');
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full font-bold {{ $badge }}">{{ $log->status_code }}</span>
                                </td>
                                <td class="py-3 px-4 text-right font-mono text-gray-600">
                                    {{ $log->response_time_ms ?? 0 }} ms
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-sm text-gray-400">
                                    Belum ada aktivitas REST API yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $logs->appends(['tab' => 'logs'])->links() }}
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-panel').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-blue-600', 'text-blue-600', 'bg-white');
                el.classList.add('border-transparent', 'text-gray-500');
            });
            document.getElementById('tab-panel-' + tab).classList.remove('hidden');
            const btn = document.getElementById('tab-btn-' + tab);
            btn.classList.add('border-blue-600', 'text-blue-600', 'bg-white');
            btn.classList.remove('border-transparent', 'text-gray-500');
        }

        function copyText(inputId, btn) {
            const input = document.getElementById(inputId);
            input.select();
            navigator.clipboard.writeText(input.value);
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="mdi mdi-check"></i><span>Tersalin!</span>';
            setTimeout(() => btn.innerHTML = original, 2000);
        }
    </script>
</x-layout>

