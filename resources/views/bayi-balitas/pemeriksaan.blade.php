<x-layout title="Pemeriksaan Bayi & Balita">
    <x-page-header 
        title="Pemeriksaan Bayi & Balita"
        subtitle="Catat dan lihat riwayat tumbuh kembang untuk <strong>{{ $bayiBalita->penduduk->nama }}</strong> (NIK: {{ $bayiBalita->penduduk->nik }})"
        icon="mdi-scale-bathroom"
        :breadcrumbs="[
            'Data Kesehatan' => null,
            ($bayiBalita->umur_bulan <= 12 ? 'Data Bayi' : 'Data Balita') => ($bayiBalita->umur_bulan <= 12 ? route('bayi-balitas.index') : route('balitas.index')),
            'Pemeriksaan' => null
        ]"
    >
        <button type="button" onclick="openAntropometriDetailModal()" 
            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200 font-semibold rounded-xl transition text-xs shadow-2xs">
            <i class="mdi mdi-information-outline text-sm"></i>
            Detail Rumus & Cara Hitung
        </button>
        <a href="{{ $bayiBalita->umur_bulan <= 12 ? route('bayi-balitas.index') : route('balitas.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold transition-all shadow-2xs">
            <i class="mdi mdi-arrow-left text-sm"></i> Kembali
        </a>
    </x-page-header>

    <!-- Alert Success/Error -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-2">
            <i class="mdi mdi-check-circle text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Row 1: Form Penimbangan & Profil Anak -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Left Side: Form Penimbangan Bulanan -->
        <div class="lg:col-span-8">
            <!-- Card 1: Input Kunjungan / Penimbangan Bulanan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-4 sm:px-8 py-4 sm:py-5 border-b border-gray-50 bg-gray-50/30">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                            <i class="mdi mdi-scale-bathroom text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-800">Catat Kunjungan & Hasil Timbangan</h3>
                    </div>
                </div>
                <div class="p-4 sm:p-8">
                    <form action="{{ route('bayi-balitas.update-pemeriksaan', $bayiBalita) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pemeriksaan <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_pemeriksaan" value="{{ old('tanggal_pemeriksaan', date('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                                @error('tanggal_pemeriksaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Umur (Bulan Ke-) <span class="text-red-500">*</span></label>
                                <select name="umur_bulan" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    @for($i = 0; $i <= 60; $i++)
                                        <option value="{{ $i }}" {{ old('umur_bulan', $bayiBalita->umur_bulan) == $i ? 'selected' : '' }}>Bulan Ke-{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('umur_bulan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Berat Badan (Kg) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="berat_badan" value="{{ old('berat_badan') }}" placeholder="0.00" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                                @error('berat_badan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tinggi / Panjang Badan (Cm) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="tinggi_badan" value="{{ old('tinggi_badan') }}" placeholder="0.00" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                                @error('tinggi_badan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Lingkar Lengan Atas (LLA - Cm)</label>
                                <input type="number" step="0.01" name="lingkar_lengan_atas" value="{{ old('lingkar_lengan_atas') }}" placeholder="0.00"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                                @error('lingkar_lengan_atas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Lingkar Kepala (LK - Cm)</label>
                                <input type="number" step="0.01" name="lingkar_kepala" value="{{ old('lingkar_kepala') }}" placeholder="0.00"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                                @error('lingkar_kepala') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">ASI Eksklusif (Usia < 6 Bln)</label>
                                <select name="asi_eksklusif"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="Tidak">Tidak</option>
                                    <option value="Ya">Ya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pemberian Vitamin A</label>
                                <select name="vitamin_a"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="tidak">Tidak Diberikan</option>
                                    <option value="biru">Kapsul Biru (100.000 IU / Bayi 6-11 Bln)</option>
                                    <option value="merah">Kapsul Merah (200.000 IU / Balita 1-5 Thn)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                            <div class="flex flex-col gap-3 justify-center">
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" name="obat_cacing" value="1" class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-sm font-bold text-gray-700 group-hover:text-blue-500 transition-colors">Pemberian Obat Cacing</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" name="pmt" value="1" class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-sm font-bold text-gray-700 group-hover:text-blue-500 transition-colors">Pemberian Makanan Tambahan (PMT)</span>
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Tumbuh Kembang</label>
                                <textarea name="catatan_perkembangan" rows="2" placeholder="Catatan tambahan mengenai kondisi fisik anak..."
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none"></textarea>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-blue-500/15 transition-all flex items-center gap-2">
                                <i class="mdi mdi-content-save"></i> Simpan Penimbangan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side: Profile Summary & Quick Link -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Profil Ringkas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                @if($bayiBalita->is_2t)
                    <div class="p-4 bg-red-50 border-b border-red-100 text-xs text-red-800 font-bold flex items-start gap-2.5">
                        <i class="mdi mdi-alert-decagram text-red-600 text-lg flex-shrink-0 mt-0.5"></i>
                        <div>
                            <div class="font-extrabold text-red-900 uppercase tracking-tight">PERINGATAN 2T (2x Tidak Naik BB)</div>
                            <p class="text-[11px] text-red-700 mt-0.5 leading-snug font-normal">Berat badan anak tidak naik 2 bulan berturut-turut. Segera berikan PMT Pemulihan & Rujukan Puskesmas.</p>
                        </div>
                    </div>
                @endif
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-500/10 rounded-lg mr-3">
                            <i class="mdi mdi-account-card-details text-blue-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Profil Anak</h3>
                    </div>
                </div>
                <div class="p-6 space-y-4 text-sm text-gray-600">
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">Nama Anak:</span>
                        <span class="text-gray-900 font-bold text-right">{{ $bayiBalita->penduduk->nama }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">NIK:</span>
                        <span class="text-gray-900 font-mono">{{ $bayiBalita->penduduk->nik }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">Tanggal Lahir:</span>
                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($bayiBalita->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">Umur Saat Ini:</span>
                        <span class="text-gray-900 font-bold text-blue-600">{{ $bayiBalita->umur_bulan }} Bulan</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">Jenis Kelamin:</span>
                        <span class="text-gray-900 capitalize">{{ $bayiBalita->penduduk->kelamin }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">Nama Ibu:</span>
                        <span class="text-gray-900">{{ $bayiBalita->nama_ibu ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">Posyandu:</span>
                        <span class="text-gray-900">{{ $bayiBalita->posyandu->nama ?? '-' }}</span>
                    </div>

                    <!-- Administrasi Akta Kelahiran -->
                    <form action="{{ route('bayi-balitas.update-pemeriksaan', $bayiBalita) }}" method="POST" class="pt-2">
                        @csrf
                        @method('PUT')
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase">Status Akta Kelahiran</label>
                        <div class="flex gap-2">
                            <select name="status_akta" 
                                class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.8rem_0.8rem] bg-[right_0.5rem_center] bg-no-repeat">
                                <option value="punya" {{ $bayiBalita->status_akta == 'punya' ? 'selected' : '' }}>Punya Akta</option>
                                <option value="tidak punya" {{ $bayiBalita->status_akta == 'tidak punya' ? 'selected' : '' }}>Tidak Punya</option>
                            </select>
                            <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 text-sm font-semibold transition">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Row 2: Riwayat Penimbangan Bulanan (FULL WIDTH) -->
    <div class="w-full">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/30">
                <form id="history-filter-form" action="{{ route('bayi-balitas.pemeriksaan', $bayiBalita) }}" method="GET">
                    <input type="hidden" name="sort" value="{{ request('sort', 'tanggal_pemeriksaan') }}">
                    <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-purple-500/10 rounded-lg mr-3 flex-shrink-0">
                                <i class="mdi mdi-history text-purple-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 whitespace-nowrap">Riwayat Penimbangan Bulanan</h3>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Search Input -->
                            <div class="relative flex-1 min-w-[220px] sm:w-72">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none">
                                    <i class="mdi mdi-magnify text-lg"></i>
                                </span>
                                <input type="text" name="search" id="history-search-input" value="{{ request('search') }}" placeholder="Cari tgl, umur, status gizi..." 
                                    class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 outline-none transition-all">
                            </div>

                            <!-- Per Page Dropdown -->
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-semibold text-gray-500 whitespace-nowrap">Tampilkan:</label>
                                <select name="per_page" onchange="this.form.submit()" 
                                    class="bg-white border border-gray-200 rounded-xl text-sm px-3 py-2 focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 outline-none appearance-none pr-8 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.5rem_center] bg-no-repeat">
                                    @foreach([5, 10, 25, 50] as $size)
                                        <option value="{{ $size }}" {{ request('per_page', 5) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Banner Information Standar WHO (Sleek & Professional) -->
            <div class="px-8 py-4 bg-gradient-to-r from-purple-50/80 via-indigo-50/40 to-blue-50/50 border-b border-purple-100/80">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="p-2 bg-purple-600 text-white rounded-xl shadow-xs">
                        <i class="mdi mdi-calculator-variant text-base"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-gray-900 text-sm tracking-tight">Standar Perhitungan Antropometri (WHO)</h4>
                        <p class="text-xs text-gray-500">Kalkulasi Z-score otomatis berdasarkan Jenis Kelamin & Umur Bulan anak</p>
                    </div>
                </div>

                <!-- Grid Legend Badges -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pt-2 border-t border-purple-100/60">
                    <!-- Status Kenaikan BB (N / T / 2T) -->
                    <div class="bg-white/90 p-2.5 rounded-xl border border-purple-100/70 flex flex-wrap items-center justify-between gap-2 text-xs shadow-2xs">
                        <span class="font-bold text-gray-700 flex items-center gap-1.5">
                            <i class="mdi mdi-trending-up text-purple-600"></i> Kenaikan BB
                        </span>
                        <div class="flex items-center gap-1 text-[11px] font-semibold">
                            <span class="px-1.5 py-0.5 bg-green-50 text-green-700 rounded border border-green-100" title="Naik BB">N</span>
                            <span class="px-1.5 py-0.5 bg-orange-50 text-orange-700 rounded border border-orange-100" title="Tidak Naik">T</span>
                            <span class="px-1.5 py-0.5 bg-red-100 text-red-800 rounded border border-red-200 font-bold" title="2x Tidak Naik (Rujukan)">2T</span>
                        </div>
                    </div>

                    <!-- Status Gizi BB/U -->
                    <div class="bg-white/90 p-2.5 rounded-xl border border-purple-100/70 flex flex-wrap items-center justify-between gap-2 text-xs shadow-2xs">
                        <span class="font-bold text-gray-700 flex items-center gap-1.5">
                            <i class="mdi mdi-weight text-purple-600"></i> Status Gizi (BB/U)
                        </span>
                        <div class="flex items-center gap-1 text-[11px] font-semibold">
                            <span class="px-2 py-0.5 bg-red-50 text-red-700 rounded-md border border-red-100" title="Z-Score < -3 SD">Sangat Kurang</span>
                            <span class="px-2 py-0.5 bg-orange-50 text-orange-700 rounded-md border border-orange-100" title="-3 s.d -2 SD">Kurang</span>
                            <span class="px-2 py-0.5 bg-green-50 text-green-700 rounded-md border border-green-100" title="-2 s.d +2 SD">Normal</span>
                        </div>
                    </div>

                    <!-- Stunting TB/U -->
                    <div class="bg-white/90 p-2.5 rounded-xl border border-purple-100/70 flex flex-wrap items-center justify-between gap-2 text-xs shadow-2xs">
                        <span class="font-bold text-gray-700 flex items-center gap-1.5">
                            <i class="mdi mdi-ruler-square text-purple-600"></i> Stunting (TB/U)
                        </span>
                        <div class="flex items-center gap-1 text-[11px] font-semibold">
                            <span class="px-2 py-0.5 bg-red-50 text-red-700 rounded-md border border-red-100" title="Z-Score < -3 SD">Sangat Pendek</span>
                            <span class="px-2 py-0.5 bg-orange-50 text-orange-700 rounded-md border border-orange-100" title="-3 s.d -2 SD">Pendek</span>
                            <span class="px-2 py-0.5 bg-green-50 text-green-700 rounded-md border border-green-100" title="-2 s.d +2 SD">Normal</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors group"
                                onclick="updateHistorySort('tanggal_pemeriksaan')">
                                <div class="flex items-center gap-1">
                                    Tgl Periksa
                                    @if(request('sort', 'tanggal_pemeriksaan') == 'tanggal_pemeriksaan')
                                        <i class="mdi mdi-arrow-{{ request('direction', 'desc') == 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="mdi mdi-arrow-up-down text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    @endif
                                </div>
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors group"
                                onclick="updateHistorySort('umur_bulan')">
                                <div class="flex items-center gap-1">
                                    Umur
                                    @if(request('sort') == 'umur_bulan')
                                        <i class="mdi mdi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="mdi mdi-arrow-up-down text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    @endif
                                </div>
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors group"
                                onclick="updateHistorySort('berat_badan')">
                                <div class="flex items-center gap-1">
                                    BB (Kg) / TB (Cm)
                                    @if(request('sort') == 'berat_badan')
                                        <i class="mdi mdi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                    @else
                                        <i class="mdi mdi-arrow-up-down text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    @endif
                                </div>
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                LLA / LK (Cm)
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors group"
                                onclick="updateHistorySort('status_gizi_bb_u')">
                                <div>
                                    <div class="flex items-center gap-1">
                                        <span>Status Gizi (BB/U)</span>
                                        @if(request('sort') == 'status_gizi_bb_u')
                                            <i class="mdi mdi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                        @else
                                            <i class="mdi mdi-arrow-up-down text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-purple-600/80 font-normal font-mono normal-case tracking-tight mt-0.5">
                                        Z = (BB - Median) &divide; SD
                                    </div>
                                </div>
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors group"
                                onclick="updateHistorySort('status_gizi_tb_u')">
                                <div>
                                    <div class="flex items-center gap-1">
                                        <span>Stunting (TB/U)</span>
                                        @if(request('sort') == 'status_gizi_tb_u')
                                            <i class="mdi mdi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} text-purple-600"></i>
                                        @else
                                            <i class="mdi mdi-arrow-up-down text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-purple-600/80 font-normal font-mono normal-case tracking-tight mt-0.5">
                                        Z = (TB - Median) &divide; SD
                                    </div>
                                </div>
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lainnya</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-150">
                        @forelse($pemeriksaanHistory as $exam)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                    {{ \Carbon\Carbon::parse($exam->tanggal_pemeriksaan)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                    {{ $exam->umur_bulan }} Bulan
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="font-bold text-gray-900">{{ $exam->berat_badan ?? '-' }} kg / {{ $exam->tinggi_badan ?? '-' }} cm</div>
                                    @if($exam->status_kenaikan_bb)
                                        <div class="mt-1 flex items-center gap-1">
                                            @if($exam->is_2t)
                                                <span class="px-2 py-0.5 bg-red-100 text-red-800 border border-red-200 rounded-md font-bold text-[10px] flex items-center gap-1" title="Berat badan tidak naik 2 bulan berturut-turut (Indikasi Rujukan)">
                                                    <i class="mdi mdi-alert-circle text-xs text-red-600"></i> 2T (Rujukan)
                                                </span>
                                            @elseif($exam->status_kenaikan_bb === 'N')
                                                <span class="px-2 py-0.5 bg-green-50 text-green-700 border border-green-200 rounded-md font-semibold text-[10px]" title="Berat Badan Naik dibanding bulan lalu">
                                                    <i class="mdi mdi-trending-up text-xs"></i> N (Naik BB)
                                                </span>
                                            @elseif($exam->status_kenaikan_bb === 'T')
                                                <span class="px-2 py-0.5 bg-orange-50 text-orange-700 border border-orange-200 rounded-md font-semibold text-[10px]" title="Berat Badan Tidak Naik / Turun dibanding bulan lalu">
                                                    <i class="mdi mdi-trending-down text-xs"></i> T (Tidak Naik)
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $exam->lingkar_lengan_atas ?? '-' }} cm / {{ $exam->lingkar_kepala ?? '-' }} cm
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full 
                                            {{ $exam->status_gizi_bb_u == 'Normal' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                            {{ $exam->status_gizi_bb_u ?? 'Normal' }}
                                        </span>
                                        @if($exam->z_score_bb_u !== null)
                                            <span class="text-[11px] font-mono font-bold text-gray-600 bg-gray-50 px-2 py-0.5 rounded border border-gray-200" title="Nilai Z-Score Standar WHO untuk Berat Badan / Umur">
                                                Z: {{ $exam->z_score_bb_u > 0 ? '+'.$exam->z_score_bb_u : $exam->z_score_bb_u }} SD
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full 
                                            {{ $exam->status_gizi_tb_u == 'Normal' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                            {{ $exam->status_gizi_tb_u ?? 'Normal' }}
                                        </span>
                                        @if($exam->z_score_tb_u !== null)
                                            <span class="text-[11px] font-mono font-bold text-gray-600 bg-gray-50 px-2 py-0.5 rounded border border-gray-200" title="Nilai Z-Score Standar WHO untuk Tinggi Badan / Umur (Stunting)">
                                                Z: {{ $exam->z_score_tb_u > 0 ? '+'.$exam->z_score_tb_u : $exam->z_score_tb_u }} SD
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600 space-y-1">
                                    @if($exam->vitamin_a && $exam->vitamin_a !== 'tidak')
                                        <div class="flex items-center gap-1 text-orange-600 font-semibold">
                                            <i class="mdi mdi-pill"></i> Vit A: {{ ucfirst($exam->vitamin_a) }}
                                        </div>
                                    @endif
                                    @if($exam->obat_cacing)
                                        <div class="flex items-center gap-1 text-teal-600 font-semibold">
                                            <i class="mdi mdi-bug-check"></i> Obat Cacing
                                        </div>
                                    @endif
                                    @if($exam->pmt)
                                        <div class="flex items-center gap-1 text-blue-600 font-semibold">
                                            <i class="mdi mdi-food-apple"></i> PMT
                                        </div>
                                    @endif
                                    @if($exam->catatan_perkembangan)
                                        <div class="text-gray-400 italic">{{ Str::limit($exam->catatan_perkembangan, 20) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" 
                                            onclick='openEditExamModal(@json($exam))' 
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                            title="Edit Penimbangan">
                                            <i class="mdi mdi-pencil text-lg"></i>
                                        </button>
                                        <form action="{{ route('pemeriksaans.destroy', $exam) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus riwayat pemeriksaan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                <i class="mdi mdi-delete text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="mdi mdi-scale-bathroom text-5xl text-gray-200"></i>
                                        <p class="mt-2 text-gray-400 font-medium">Belum ada riwayat penimbangan bulanan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Formula Summary Bar at Bottom of Table -->
            <div class="px-6 py-3 bg-purple-50/60 border-t border-purple-100/80 flex flex-wrap items-center justify-between gap-2 text-xs text-purple-950">
                <div class="flex items-center gap-1.5 font-bold">
                    <i class="mdi mdi-calculator text-purple-600 text-sm"></i>
                    <span>Rumus Perhitungan Kolom Tabel:</span>
                </div>
                <div class="flex flex-wrap items-center gap-2 font-mono text-[11px]">
                    <span class="bg-white px-2.5 py-1 rounded-lg border border-purple-200 shadow-2xs">Status Gizi (BB/U): <strong class="text-purple-700">Z = (BB Hasil - Median BB WHO) &divide; SD</strong></span>
                    <span class="bg-white px-2.5 py-1 rounded-lg border border-purple-200 shadow-2xs">Stunting (TB/U): <strong class="text-purple-700">Z = (TB Hasil - Median TB WHO) &divide; SD</strong></span>
                </div>
            </div>

            <div class="p-4 border-t border-gray-100 bg-gray-50/30">
                {{ $pemeriksaanHistory->links() }}
            </div>
        </div>
    </div>

    <!-- Edit Pemeriksaan Modal -->
    <div id="edit-exam-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeEditExamModal()"></div>

        <!-- Modal Container -->
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-blue-500/10 rounded-lg mr-3">
                            <i class="mdi mdi-pencil text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Edit Riwayat Penimbangan</h3>
                            <p class="text-xs text-gray-500">Perbarui data hasil penimbangan bulanan anak</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeEditExamModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>

                <!-- Form -->
                <form id="edit-exam-form" action="" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pemeriksaan <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_pemeriksaan" id="edit_tanggal_pemeriksaan" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Umur (Bulan Ke-) <span class="text-red-500">*</span></label>
                                <select name="umur_bulan" id="edit_umur_bulan" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    @for($i = 0; $i <= 60; $i++)
                                        <option value="{{ $i }}">Bulan Ke-{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Berat Badan (Kg) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="berat_badan" id="edit_berat_badan" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tinggi Badan (Cm) <span class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="tinggi_badan" id="edit_tinggi_badan" required
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Lingkar Lengan Atas (LLA - Cm)</label>
                                <input type="number" step="0.01" name="lingkar_lengan_atas" id="edit_lingkar_lengan_atas"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Lingkar Kepala (LK - Cm)</label>
                                <input type="number" step="0.01" name="lingkar_kepala" id="edit_lingkar_kepala"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">ASI Eksklusif</label>
                                <select name="asi_eksklusif" id="edit_asi_eksklusif"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.5rem_center] bg-no-repeat">
                                    <option value="Tidak">Tidak</option>
                                    <option value="Ya">Ya</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pemberian Vitamin A</label>
                                <select name="vitamin_a" id="edit_vitamin_a"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="tidak">Tidak Diberikan</option>
                                    <option value="biru">Kapsul Biru (100.000 IU)</option>
                                    <option value="merah">Kapsul Merah (200.000 IU)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                            <div class="flex flex-col gap-3 justify-center">
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" name="obat_cacing" id="edit_obat_cacing" value="1" class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-sm font-bold text-gray-700 group-hover:text-blue-500 transition-colors">Pemberian Obat Cacing</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" name="pmt" id="edit_pmt" value="1" class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-sm font-bold text-gray-700 group-hover:text-blue-500 transition-colors">Pemberian PMT</span>
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Tumbuh Kembang</label>
                                <textarea name="catatan_perkembangan" id="edit_catatan_perkembangan" rows="2"
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" onclick="closeEditExamModal()"
                            class="px-5 py-2.5 bg-white text-gray-700 font-semibold rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/15 transition flex items-center gap-2">
                            <i class="mdi mdi-content-save"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Rumus & Standar Antropometri WHO (Kemenkes RI) -->
    <div id="antropometri-detail-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 md:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <button onclick="closeAntropometriDetailModal()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
                <i class="mdi mdi-close text-xl"></i>
            </button>

            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <i class="mdi mdi-calculator-variant text-xl"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Batas Normal & Standar Rumus Antropometri (Kemenkes RI)</h3>
                    <p class="text-xs text-gray-500">Pedoman Standar Antropometri Anak & Kriteria WHO</p>
                </div>
            </div>

            <div class="space-y-4 text-xs">
                <!-- 1. Rumus Utama Z-Score -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-1.5">
                        <i class="mdi mdi-function-variant text-purple-600"></i> Rumus Utama Z-Score Antropometri:
                    </h4>
                    <div class="p-2.5 bg-white rounded-xl border border-gray-200 font-mono text-center text-purple-900 font-bold mb-2">
                        Z-Score = (Nilai Pengukuran Anak - Median Standar WHO) / Standar Deviasi (SD)
                    </div>
                    <ul class="space-y-1 text-gray-600 pl-4 list-disc">
                        <li><strong>Nilai Pengukuran</strong>: Hasil penimbangan BB (kg) atau pengukuran TB/PB (cm).</li>
                        <li><strong>Median Standar WHO</strong>: Nilai tengah populasi acuan normal WHO sesuai umur (bulan) & jenis kelamin.</li>
                        <li><strong>Standar Deviasi (SD)</strong>: Nilai simpangan baku WHO untuk kelompok umur anak.</li>
                    </ul>
                </div>

                <!-- Indikator Kenaikan BB (N, T, dan 2T) -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-1.5">
                        <i class="mdi mdi-trending-up text-purple-600"></i> Indikator Kenaikan BB (N, T, dan 2T Kemenkes RI):
                    </h4>
                    <ul class="space-y-1 text-gray-600 pl-4 list-disc">
                        <li><strong>N (Naik)</strong> : Hasil penimbangan BB bulan ini meningkat dibanding bulan sebelumnya.</li>
                        <li><strong>T (Tidak Naik)</strong> : Hasil penimbangan BB bulan ini tetap atau mengalami penurunan.</li>
                        <li><strong>2T (Dua Kali Tidak Naik)</strong> : BB anak bernilai <strong>T</strong> selama <strong>2 bulan berturut-turut</strong>. Terindikasi <em>gagal tumbuh / KEK</em> dan wajib diberikan konseling gizi, PMT Pemulihan, atau rujukan ke Puskesmas.</li>
                    </ul>
                </div>

                <!-- 2. Klasifikasi Stunting (TB/U) -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-1.5">
                        <i class="mdi mdi-ruler-square text-purple-600"></i> Kriteria Stunting / Panjang (Tinggi) Badan menurut Umur (TB/U):
                    </h4>
                    <ul class="space-y-1 text-gray-600 pl-4 list-disc">
                        <li><strong>< -3.0 SD</strong> : Sangat Pendek (Severely Stunted)</li>
                        <li><strong>-3.0 SD s.d. -2.0 SD</strong> : Pendek / Stunting (Stunted)</li>
                        <li><strong>-2.0 SD s.d. +2.0 SD</strong> : Normal (Pertumbuhan Optimal)</li>
                        <li><strong>> +2.0 SD</strong> : Tinggi</li>
                    </ul>
                </div>

                <!-- 3. Klasifikasi Status Gizi (BB/U) -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-1.5">
                        <i class="mdi mdi-weight text-purple-600"></i> Kriteria Status Gizi / Berat Badan menurut Umur (BB/U):
                    </h4>
                    <ul class="space-y-1 text-gray-600 pl-4 list-disc">
                        <li><strong>< -3.0 SD</strong> : Berat Badan Sangat Kurang (Severely Underweight)</li>
                        <li><strong>-3.0 SD s.d. -2.0 SD</strong> : Berat Badan Kurang (Underweight)</li>
                        <li><strong>-2.0 SD s.d. +1.0 SD</strong> : Berat Badan Normal</li>
                        <li><strong>> +1.0 SD</strong> : Risiko Berat Badan Lebih</li>
                    </ul>
                </div>

                <!-- 4. Contoh Kasus -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-1.5">
                        <i class="mdi mdi-lightbulb-on text-amber-500"></i> Contoh Simulasi Perhitungan Stunting:
                    </h4>
                    <p class="text-gray-700 mb-1.5">Anak Laki-Laki 12 Bulan dengan TB = 68.0 cm:</p>
                    <ul class="space-y-1 text-gray-600 pl-4 list-disc">
                        <li>Acuan WHO (Laki-Laki 12 Bln): Median TB = 75.7 cm, SD = 3.4 cm</li>
                        <li>Selisih pengukuran: 68.0 - 75.7 = -7.7 cm</li>
                        <li>Nilai Z-Score: -7.7 / 3.4 = <strong>-2.26 SD</strong></li>
                        <li>Diagnosa: Berada pada rentang -3.0 s.d. -2.0 SD &rarr; <span class="font-bold text-orange-700">Pendek (Stunting)</span></li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <span class="text-[11px] text-gray-400">Permenkes RI No. 2 Tahun 2020</span>
                <button onclick="closeAntropometriDetailModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl text-xs hover:bg-gray-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        function openAntropometriDetailModal() {
            document.getElementById('antropometri-detail-modal').classList.remove('hidden');
        }

        function closeAntropometriDetailModal() {
            document.getElementById('antropometri-detail-modal').classList.add('hidden');
        }

        function openEditExamModal(exam) {
            const form = document.getElementById('edit-exam-form');
            form.action = `/pemeriksaans/${exam.id}`;

            document.getElementById('edit_tanggal_pemeriksaan').value = exam.tanggal_pemeriksaan || '';
            document.getElementById('edit_umur_bulan').value = exam.umur_bulan !== null ? exam.umur_bulan : 0;
            document.getElementById('edit_berat_badan').value = exam.berat_badan || '';
            document.getElementById('edit_tinggi_badan').value = exam.tinggi_badan || '';
            document.getElementById('edit_lingkar_lengan_atas').value = exam.lingkar_lengan_atas || '';
            document.getElementById('edit_lingkar_kepala').value = exam.lingkar_kepala || '';
            document.getElementById('edit_asi_eksklusif').value = exam.asi_eksklusif || 'Tidak';
            document.getElementById('edit_vitamin_a').value = exam.vitamin_a || 'tidak';
            document.getElementById('edit_obat_cacing').checked = !!exam.obat_cacing;
            document.getElementById('edit_pmt').checked = !!exam.pmt;
            document.getElementById('edit_catatan_perkembangan').value = exam.catatan_perkembangan || '';

            document.getElementById('edit-exam-modal').classList.remove('hidden');
        }

        function closeEditExamModal() {
            document.getElementById('edit-exam-modal').classList.add('hidden');
        }

        function updateHistorySort(field) {
            const form = document.getElementById('history-filter-form');
            if (!form) return;
            const currentField = form.sort.value;
            const currentDirection = form.direction.value;

            if (currentField === field) {
                form.direction.value = currentDirection === 'asc' ? 'desc' : 'asc';
            } else {
                form.sort.value = field;
                form.direction.value = 'asc';
            }
            form.submit();
        }

        let historySearchTimeout;
        const historySearchInput = document.getElementById('history-search-input');
        if (historySearchInput) {
            historySearchInput.addEventListener('input', function() {
                clearTimeout(historySearchTimeout);
                historySearchTimeout = setTimeout(() => {
                    document.getElementById('history-filter-form').submit();
                }, 500);
            });
        }
    </script>
</x-layout>
