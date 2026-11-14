<x-layout title="Dokumentasi REST API">
    <x-page-header
        title="Dokumentasi REST API"
        subtitle="Panduan Integrasi, Daftar Endpoint Seluruh Data Posyandu, & Uji Coba Interaktif"
        icon="mdi-book-open-page-variant"
        :breadcrumbs="[['label' => 'Manajemen API', 'url' => route('admin.api.index')], ['label' => 'Dokumentasi API']]"
    >
        <a href="{{ route('admin.api.index') }}"
            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-xl text-xs font-bold transition">
            <i class="mdi mdi-cog-outline text-sm"></i>
            <span>Pengaturan & API Key</span>
        </a>
    </x-page-header>

    <!-- Informasi Dasar & Autentikasi -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-2xs">
            <div class="flex items-center gap-2.5 mb-2">
                <span class="p-2 bg-blue-50 text-blue-600 rounded-xl"><i class="mdi mdi-web text-lg"></i></span>
                <h3 class="font-bold text-gray-800 text-sm">Base URL</h3>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 font-mono text-xs text-gray-800 break-all">
                {{ $baseUrl }}
            </div>
            <p class="text-xs text-gray-500 mt-2">Semua endpoint mengembalikan format <code>application/json</code>.</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-2xs">
            <div class="flex items-center gap-2.5 mb-2">
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl"><i class="mdi mdi-shield-key-outline text-lg"></i></span>
                <h3 class="font-bold text-gray-800 text-sm">Autentikasi</h3>
            </div>
            <p class="text-xs text-gray-600 leading-relaxed">
                Status saat ini:
                <strong class="{{ $settings['api_auth_required'] === '1' ? 'text-blue-700' : 'text-amber-700' }}">
                    {{ $settings['api_auth_required'] === '1' ? 'Wajib API Key' : 'Publik (Opsional Key)' }}
                </strong>.
                Sertakan salah satu header berikut:
            </p>
            <div class="mt-2 space-y-1 text-[11px] font-mono bg-gray-50 p-2.5 rounded-xl border border-gray-200 text-gray-700">
                <div>X-API-Key: psy_live_xxxx</div>
                <div>Authorization: Bearer psy_live_xxxx</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-2xs">
            <div class="flex items-center gap-2.5 mb-2">
                <span class="p-2 bg-purple-50 text-purple-600 rounded-xl"><i class="mdi mdi-filter-variant text-lg"></i></span>
                <h3 class="font-bold text-gray-800 text-sm">Paginasi & Filter Global</h3>
            </div>
            <ul class="text-xs text-gray-600 space-y-1">
                <li><code>?page=1&amp;per_page={{ $settings['api_default_per_page'] }}</code> (maks {{ $settings['api_max_per_page'] }})</li>
                <li><code>?all=1</code> — Ambil seluruh data tanpa paginasi</li>
                <li><code>?search=kata_kunci</code> — Pencarian nama/NIK/judul</li>
                <li><code>?dusun=...&amp;rw=...&amp;rt=...</code> — Filter wilayah</li>
            </ul>
        </div>
    </div>

    <!-- Interactive API Tester -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-2xs p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <i class="mdi mdi-play-circle text-blue-600 text-lg"></i>
                <span>Uji Coba Endpoint Langsung (API Playground)</span>
            </h3>
            <span class="text-[11px] text-gray-400">Rate limit aktif: {{ $settings['api_rate_limit'] }} req/menit</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <div class="md:col-span-4">
                <label class="block text-[11px] font-bold text-gray-600 mb-1">Pilih Endpoint</label>
                <select id="testerEndpoint" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-mono focus:border-blue-500 focus:outline-none">
                    @foreach($endpointGroups as $grp)
                        <optgroup label="{{ $grp['group'] }}">
                            @foreach($grp['endpoints'] as $ep)
                                @if(!str_contains($ep['path'], '{id}'))
                                    <option value="{{ $ep['path'] }}">{{ $ep['method'] }} {{ $ep['path'] }}</option>
                                @endif
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="block text-[11px] font-bold text-gray-600 mb-1">Query String (Opsional)</label>
                <input type="text" id="testerQuery" placeholder="per_page=5&search="
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-mono focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-3">
                <label class="block text-[11px] font-bold text-gray-600 mb-1">X-API-Key (Jika wajib auth)</label>
                <input type="text" id="testerKey" placeholder="psy_live_..."
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-mono focus:border-blue-500 focus:outline-none">
            </div>
            <div class="md:col-span-2 flex items-end">
                <button type="button" onclick="runApiTest()"
                    class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition cursor-pointer flex items-center justify-center gap-1.5">
                    <i class="mdi mdi-send"></i>
                    <span>Kirim</span>
                </button>
            </div>
        </div>

        <div id="testerResultBox" class="mt-4 hidden">
            <div class="flex items-center justify-between text-xs mb-1.5">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-600">Respons:</span>
                    <span id="testerStatus" class="px-2 py-0.5 rounded-full font-bold"></span>
                    <span id="testerTime" class="text-gray-400 font-mono"></span>
                </div>
                <span id="testerUrl" class="text-[11px] font-mono text-gray-400 truncate max-w-md"></span>
            </div>
            <pre id="testerOutput" class="bg-slate-900 text-emerald-300 p-4 rounded-xl text-xs font-mono overflow-x-auto max-h-80"></pre>
        </div>
    </div>

    <!-- Daftar Lengkap Endpoint -->
    <div class="space-y-5 mb-6">
        @foreach($endpointGroups as $grp)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-2xs overflow-hidden">
                <div class="px-5 py-3.5 bg-gray-50/70 border-b border-gray-100 flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        <i class="mdi {{ $grp['icon'] }} text-lg text-blue-600"></i>
                        <h4 class="font-bold text-gray-800 text-sm">{{ $grp['group'] }}</h4>
                    </div>
                    <span class="text-[11px] font-mono px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                        Scope: {{ $grp['ability'] }}
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase">
                                <th class="py-2.5 px-5 w-24">Method</th>
                                <th class="py-2.5 px-5 w-64">Endpoint</th>
                                <th class="py-2.5 px-5">Deskripsi</th>
                                <th class="py-2.5 px-5">Parameter Query</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-xs">
                            @foreach($grp['endpoints'] as $ep)
                                <tr class="hover:bg-gray-50/60">
                                    <td class="py-3 px-5">
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-bold font-mono text-[11px]">
                                            {{ $ep['method'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-5 font-mono font-semibold text-gray-800">
                                        /api/v1{{ $ep['path'] }}
                                    </td>
                                    <td class="py-3 px-5 text-gray-600">{{ $ep['desc'] }}</td>
                                    <td class="py-3 px-5 font-mono text-[11px] text-gray-500">{{ $ep['params'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Contoh Kode & Format Response -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-2xs p-5">
            <h4 class="font-bold text-gray-800 text-sm mb-3 flex items-center gap-2">
                <i class="mdi mdi-code-tags text-blue-600"></i>
                <span>Contoh Pemanggilan (cURL &amp; JavaScript)</span>
            </h4>
            <pre class="bg-slate-900 text-slate-100 p-4 rounded-xl text-xs font-mono overflow-x-auto"># cURL
curl -X GET "{{ $baseUrl }}/penduduks?per_page=10" \
  -H "Accept: application/json" \
  -H "X-API-Key: TOKEN_API_ANDA"

# JavaScript (Fetch)
fetch("{{ $baseUrl }}/statistik", {
  headers: {
    "Accept": "application/json",
    "Authorization": "Bearer TOKEN_API_ANDA"
  }
}).then(r => r.json()).then(console.log);</pre>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-2xs p-5">
            <h4 class="font-bold text-gray-800 text-sm mb-3 flex items-center gap-2">
                <i class="mdi mdi-code-json text-emerald-600"></i>
                <span>Format Standar Response &amp; Kode Status HTTP</span>
            </h4>
            <pre class="bg-slate-900 text-emerald-300 p-4 rounded-xl text-xs font-mono overflow-x-auto mb-3">{
  "success": true,
  "message": "Berhasil mengambil data penduduk.",
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 120,
    "last_page": 8
  }
}</pre>
            <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                <div><code class="font-bold text-emerald-700">200</code> OK — Berhasil</div>
                <div><code class="font-bold text-amber-700">401</code> Unauthorized — Token kosong/salah</div>
                <div><code class="font-bold text-amber-700">403</code> Forbidden — Scope/IP ditolak</div>
                <div><code class="font-bold text-amber-700">429</code> Too Many Requests — Rate limit</div>
                <div><code class="font-bold text-red-700">404</code> Not Found — Data tidak ada</div>
                <div><code class="font-bold text-red-700">503</code> Service Unavailable — API nonaktif</div>
            </div>
        </div>
    </div>

    <script>
        async function runApiTest() {
            const path = document.getElementById('testerEndpoint').value;
            const query = document.getElementById('testerQuery').value.trim();
            const key = document.getElementById('testerKey').value.trim();
            const url = `{{ $baseUrl }}${path}` + (query ? `?${query.replace(/^\?/, '')}` : '');

            const box = document.getElementById('testerResultBox');
            const statusEl = document.getElementById('testerStatus');
            const timeEl = document.getElementById('testerTime');
            const urlEl = document.getElementById('testerUrl');
            const outEl = document.getElementById('testerOutput');

            box.classList.remove('hidden');
            urlEl.textContent = url;
            outEl.textContent = 'Mengirim request...';

            const headers = { 'Accept': 'application/json' };
            if (key) headers['X-API-Key'] = key;

            const t0 = performance.now();
            try {
                const res = await fetch(url, { headers });
                const ms = Math.round(performance.now() - t0);
                const text = await res.text();
                let formatted = text;
                try { formatted = JSON.stringify(JSON.parse(text), null, 2); } catch (_) {}

                statusEl.textContent = `${res.status} ${res.statusText}`;
                statusEl.className = 'px-2 py-0.5 rounded-full font-bold ' +
                    (res.ok ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800');
                timeEl.textContent = `${ms} ms`;
                outEl.textContent = formatted;
            } catch (err) {
                statusEl.textContent = 'ERROR';
                statusEl.className = 'px-2 py-0.5 rounded-full font-bold bg-red-100 text-red-800';
                outEl.textContent = err.message;
            }
        }
    </script>
</x-layout>

