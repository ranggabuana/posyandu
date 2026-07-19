<x-layout>
    <x-slot:title>Pemeriksaan Kesehatan Lansia - Posyandu Banjar</x-slot:title>

    <!-- Header & Breadcrumbs -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <i class="mdi mdi-chevron-right text-gray-400"></i>
                <a href="{{ route('lansias.index') }}" class="hover:text-blue-600">Data Lansia</a>
                <i class="mdi mdi-chevron-right text-gray-400"></i>
                <span class="text-gray-900 font-semibold">Pemeriksaan Kesehatan</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
                <i class="mdi mdi-heart-pulse text-red-500 text-3xl"></i>
                Pemeriksaan Kesehatan Lansia
            </h1>
        </div>

        <a href="{{ route('lansias.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition shadow-2xs text-xs self-start sm:self-auto">
            <i class="mdi mdi-arrow-left"></i>
            Kembali ke Daftar Lansia
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-sm shadow-xs">
            <i class="mdi mdi-check-circle text-emerald-500 text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Profile Card Lansia (Left Sidebar) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-red-100/50 to-pink-100/30 rounded-bl-full -z-0"></div>
                
                <div class="relative z-10">
                    @php
                        $isLaki = in_array(strtolower($lansia->penduduk->kelamin ?? ''), ['laki-laki', 'l']);
                    @endphp
                    <div class="mb-5">
                        <span class="px-2.5 py-0.5 {{ $isLaki ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-pink-50 text-pink-700 border-pink-100' }} font-bold text-[11px] rounded-full border inline-flex items-center gap-1">
                            {{ $isLaki ? 'Laki-Laki' : 'Perempuan' }} ({{ $lansia->penduduk->umur ?? $lansia->penduduk->usia ?? '-' }} Thn)
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 mt-1 leading-snug">
                            {{ $lansia->penduduk->nama }}
                        </h3>
                        <p class="text-xs text-gray-500 font-mono">NIK: {{ $lansia->penduduk->nik }}</p>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-gray-100 text-xs">
                        <div class="flex justify-between items-center py-1">
                            <span class="text-gray-500 flex items-center gap-1.5">
                                <i class="mdi mdi-gender-male-female text-gray-400"></i> Jenis Kelamin
                            </span>
                            <span class="font-semibold text-gray-800 capitalize">{{ $lansia->penduduk->kelamin }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-gray-500 flex items-center gap-1.5">
                                <i class="mdi mdi-map-marker-outline text-gray-400"></i> Wilayah Posyandu
                            </span>
                            <span class="font-semibold text-gray-800">
                                {{ $lansia->penduduk->dusun ?? '-' }} (RW {{ $lansia->penduduk->rw ?? '00' }}/RT {{ $lansia->penduduk->rt ?? '00' }})
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-gray-500 flex items-center gap-1.5">
                                <i class="mdi mdi-hospital-building text-gray-400"></i> Posyandu
                            </span>
                            <span class="font-semibold text-gray-800">{{ $lansia->posyandu->nama ?? 'Posyandu Banjar' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Input Pemeriksaan (Right Side) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="p-2.5 bg-red-50 text-red-600 rounded-2xl">
                        <i class="mdi mdi-clipboard-plus text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Form Input Pemeriksaan Lansia</h3>
                        <p class="text-xs text-gray-500">Masukkan parameter pemeriksaan fisik dan medis Lansia/PTM</p>
                    </div>
                </div>

                <form action="{{ route('lansias.store-pemeriksaan', $lansia->id) }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Row 1: Tanggal & Fisik -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Pemeriksaan *</label>
                            <input type="date" name="tanggal_pemeriksaan" value="{{ old('tanggal_pemeriksaan', date('Y-m-d')) }}" required
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Berat Badan (Kg)</label>
                            <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan') }}" placeholder="Contoh: 62.5"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tinggi Badan (Cm)</label>
                            <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan') }}" placeholder="Contoh: 160"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none">
                        </div>
                    </div>

                    <!-- Row 2: Tensi & Lingkar Perut -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tensi Sistolik (mmHg)</label>
                            <input type="number" name="tensi_sistolik" value="{{ old('tensi_sistolik') }}" placeholder="Contoh: 120"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tensi Diastolik (mmHg)</label>
                            <input type="number" name="tensi_diastolik" value="{{ old('tensi_diastolik') }}" placeholder="Contoh: 80"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Lingkar Perut (Cm)</label>
                            <input type="number" step="0.1" name="lingkar_perut" value="{{ old('lingkar_perut') }}" placeholder="Contoh: 85"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none">
                        </div>
                    </div>

                    <!-- Row 3: Laboratorium Sederhana (Gula, Kolesterol, Asam Urat) -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-red-50/40 rounded-2xl border border-red-100">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Gula Darah (mg/dL)</label>
                            <input type="number" name="gula_darah" value="{{ old('gula_darah') }}" placeholder="Contoh: 110"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Tes Gula</label>
                            <select name="jenis_gula_darah"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none">
                                <option value="sewaktu">Gula Sewaktu</option>
                                <option value="puasa">Gula Puasa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kolesterol (mg/dL)</label>
                            <input type="number" name="kolesterol" value="{{ old('kolesterol') }}" placeholder="Contoh: 180"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Asam Urat (mg/dL)</label>
                            <input type="number" step="0.1" name="asam_urat" value="{{ old('asam_urat') }}" placeholder="Contoh: 5.5"
                                class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none">
                        </div>
                    </div>

                    <!-- Row 4: Keluhan & Catatan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Keluhan Lansia</label>
                            <textarea name="keluhan" rows="2" placeholder="Contoh: Pusing, pegal di pundak, nyeri sendi..."
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Catatan / Rujukan Dokter</label>
                            <textarea name="catatan" rows="2" placeholder="Contoh: Kurangi konsumsi garam, konsultasi ke Puskesmas..."
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-md shadow-red-500/20 transition flex items-center gap-2">
                            <i class="mdi mdi-content-save"></i>
                            Simpan Hasil Pemeriksaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Riwayat Pemeriksaan Table (Full-Width Section) -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        
        <!-- Banner Standar Parameter Kemenkes RI di Atas Tabel -->
        <div class="px-6 py-3.5 bg-gradient-to-r from-red-50 via-pink-50/50 to-amber-50/40 border-b border-red-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-red-600 text-white rounded-xl shadow-xs">
                    <i class="mdi mdi-heart-pulse text-lg"></i>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 text-xs">Standar Acuan Skrining Kesehatan Lansia (Kemenkes RI)</h4>
                    <p class="text-[11px] text-gray-500">Kalkulasi otomatis status Hipertensi, Diabetes, Kolesterol, Asam Urat & IMT</p>
                </div>
            </div>
            <button type="button" onclick="openLansiaRefModal()" 
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-xl shadow-xs text-xs font-bold transition self-start sm:self-auto">
                <i class="mdi mdi-information-outline text-sm"></i>
                <span>Detail Standar Medis</span>
            </button>
        </div>

        <!-- Table Control Toolbar -->
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="mdi mdi-history text-red-500"></i>
                    Riwayat Pemeriksaan Kesehatan Lansia
                </h3>
                <p class="text-xs text-gray-500">Daftar rekam medis & skrining kesehatan lansia secara berkala</p>
            </div>

            <!-- Form Filter Toolbar -->
            <form id="lansia-history-filter-form" action="{{ route('lansias.pemeriksaan', $lansia->id) }}" method="GET" class="flex flex-wrap items-center gap-3">
                <input type="hidden" name="sort" value="{{ request('sort', 'tanggal_pemeriksaan') }}">
                <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">

                <!-- Search -->
                <div class="relative">
                    <i class="mdi mdi-magnify absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" id="lansia-history-search" value="{{ request('search') }}" placeholder="Cari tanggal/keluhan..."
                        class="pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 focus:ring-2 focus:ring-red-500/20 outline-none w-48 sm:w-64">
                </div>

                <!-- Per Page -->
                <select name="per_page" onchange="this.form.submit()" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-900 outline-none">
                    <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 Baris</option>
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Baris</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                </select>
            </form>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition"
                            onclick="updateLansiaSort('tanggal_pemeriksaan')">
                            Tgl Periksa
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">BB / TB / IMT</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tekanan Darah (Tensi)</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Gula Darah</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Kolesterol</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Asam Urat</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Keluhan</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-150">
                    @forelse($pemeriksaanHistory as $exam)
                        <tr class="hover:bg-red-50/30 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-900 font-bold">
                                {{ \Carbon\Carbon::parse($exam->tanggal_pemeriksaan)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-700">
                                <div>{{ $exam->berat_badan ?? '-' }} kg / {{ $exam->tinggi_badan ?? '-' }} cm</div>
                                @if($exam->status_imt)
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full mt-1 inline-block
                                        {{ $exam->status_imt == 'Normal' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-orange-50 text-orange-700 border border-orange-200' }}">
                                        IMT: {{ $exam->imt ?? '-' }} ({{ $exam->status_imt }})
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-700">
                                <div class="font-mono font-bold">{{ $exam->tensi_sistolik ?? '-' }}/{{ $exam->tensi_diastolik ?? '-' }} mmHg</div>
                                @if($exam->status_tensi)
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full mt-1 inline-block
                                        {{ $exam->status_tensi == 'Normal' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                        {{ $exam->status_tensi }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-700">
                                <div>{{ $exam->gula_darah ? $exam->gula_darah . ' mg/dL' : '-' }}</div>
                                @if($exam->status_gula_darah)
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full mt-1 inline-block
                                        {{ $exam->status_gula_darah == 'Normal' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                        {{ $exam->status_gula_darah }} ({{ ucfirst($exam->jenis_gula_darah) }})
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-700">
                                <div>{{ $exam->kolesterol ? $exam->kolesterol . ' mg/dL' : '-' }}</div>
                                @if($exam->status_kolesterol)
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full mt-1 inline-block
                                        {{ $exam->status_kolesterol == 'Normal' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                        {{ $exam->status_kolesterol }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-700">
                                <div>{{ $exam->asam_urat ? $exam->asam_urat . ' mg/dL' : '-' }}</div>
                                @if($exam->status_asam_urat)
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full mt-1 inline-block
                                        {{ $exam->status_asam_urat == 'Normal' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                        {{ $exam->status_asam_urat }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600 max-w-xs">
                                <div class="font-medium text-gray-800">{{ Str::limit($exam->keluhan, 25) ?? '-' }}</div>
                                @if($exam->catatan)
                                    <div class="text-[10px] text-gray-500 italic mt-0.5">{{ Str::limit($exam->catatan, 25) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                <div class="flex items-center justify-end gap-1">
                                    <button type="button" onclick='openEditLansiaExamModal(@json($exam))'
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <i class="mdi mdi-pencil text-base"></i>
                                    </button>

                                    <form action="{{ route('pemeriksaan-lansias.destroy', $exam->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat pemeriksaan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                            <i class="mdi mdi-delete text-base"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400 text-xs">
                                <i class="mdi mdi-heart-broken text-5xl text-gray-200 block mb-2"></i>
                                Belum ada riwayat pemeriksaan lansia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100 bg-gray-50/30">
            {{ $pemeriksaanHistory->links() }}
        </div>
    </div>

    <!-- Edit Pemeriksaan Lansia Modal -->
    <div id="edit-lansia-exam-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeEditLansiaExamModal()"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-red-50 to-pink-50 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <i class="mdi mdi-pencil-box text-red-600 text-2xl"></i>
                        <h3 class="text-base font-bold text-gray-900">Edit Pemeriksaan Lansia</h3>
                    </div>
                    <button type="button" onclick="closeEditLansiaExamModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>

                <form id="edit-lansia-exam-form" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal Pemeriksaan</label>
                            <input type="date" name="tanggal_pemeriksaan" id="edit_tanggal_pemeriksaan" required
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Berat Badan (Kg)</label>
                            <input type="number" step="0.1" name="berat_badan" id="edit_berat_badan"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tinggi Badan (Cm)</label>
                            <input type="number" step="0.1" name="tinggi_badan" id="edit_tinggi_badan"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tensi Sistolik</label>
                            <input type="number" name="tensi_sistolik" id="edit_tensi_sistolik"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Tensi Diastolik</label>
                            <input type="number" name="tensi_diastolik" id="edit_tensi_diastolik"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Lingkar Perut (Cm)</label>
                            <input type="number" step="0.1" name="lingkar_perut" id="edit_lingkar_perut"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 p-3 bg-red-50/40 rounded-xl">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Gula Darah</label>
                            <input type="number" name="gula_darah" id="edit_gula_darah"
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Gula</label>
                            <select name="jenis_gula_darah" id="edit_jenis_gula_darah" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs outline-none">
                                <option value="sewaktu">Sewaktu</option>
                                <option value="puasa">Puasa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kolesterol</label>
                            <input type="number" name="kolesterol" id="edit_kolesterol"
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Asam Urat</label>
                            <input type="number" step="0.1" name="asam_urat" id="edit_asam_urat"
                                class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Keluhan Lansia</label>
                            <textarea name="keluhan" id="edit_keluhan" rows="2"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Catatan Dokter/Kader</label>
                            <textarea name="catatan" id="edit_catatan" rows="2"
                                class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs outline-none"></textarea>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex justify-end gap-2">
                        <button type="button" onclick="closeEditLansiaExamModal()" class="px-4 py-2 bg-gray-100 text-gray-700 text-xs font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-red-600 text-white text-xs font-bold rounded-xl shadow-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Standar & Panduan Skrining Lansia -->
    <div id="lansia-ref-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeLansiaRefModal()"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-red-50 to-pink-50 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-red-600 text-white rounded-xl shadow-xs">
                            <i class="mdi mdi-shield-check text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Batas Normal & Standar Diagnosa Skrining Lansia</h3>
                            <p class="text-xs text-red-700 font-medium">Acuan Resmi Posyandu Lansia & PTM Kementerian Kesehatan RI</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeLansiaRefModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>

                <div class="p-6 space-y-5 text-xs text-gray-700 max-h-[75vh] overflow-y-auto">
                    <!-- Tabel Acuan Paramater Medis -->
                    <div class="overflow-hidden border border-gray-200 rounded-2xl shadow-2xs">
                        <table class="min-w-full divide-y divide-gray-200 text-xs">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-bold text-gray-700">Parameter Skrining</th>
                                    <th class="px-4 py-3 text-left font-bold text-gray-700">Rentang Normal</th>
                                    <th class="px-4 py-3 text-left font-bold text-gray-700">Kategori Risiko / Diagnosa</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-150">
                                <tr>
                                    <td class="px-4 py-3 font-bold text-gray-900">Tekanan Darah (Tensi)</td>
                                    <td class="px-4 py-3 font-mono font-bold text-green-700">&lt; 120/80 mmHg</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        • Normal: &lt;120/80 mmHg<br>
                                        • Prehipertensi: 120-139 / 80-89 mmHg<br>
                                        • Hipertensi D1: 140-159 / 90-99 mmHg<br>
                                        • Hipertensi D2: &ge;160 / &ge;100 mmHg
                                    </td>
                                </tr>
                                <tr class="bg-red-50/20">
                                    <td class="px-4 py-3 font-bold text-gray-900">Gula Darah (Sewaktu)</td>
                                    <td class="px-4 py-3 font-mono font-bold text-green-700">&lt; 140 mg/dL</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        • Normal: &lt;140 mg/dL<br>
                                        • Toleransi Terganggu: 140-199 mg/dL<br>
                                        • Diabetes Melitus: &ge;200 mg/dL
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-bold text-gray-900">Kolesterol Total</td>
                                    <td class="px-4 py-3 font-mono font-bold text-green-700">&lt; 200 mg/dL</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        • Normal: &lt;200 mg/dL<br>
                                        • Batas Tinggi: 200-239 mg/dL<br>
                                        • Tinggi: &ge;240 mg/dL
                                    </td>
                                </tr>
                                <tr class="bg-red-50/20">
                                    <td class="px-4 py-3 font-bold text-gray-900">Asam Urat</td>
                                    <td class="px-4 py-3 font-mono font-bold text-green-700">L &le; 7.0 / P &le; 6.0 mg/dL</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        • Normal: Laki &le;7.0 mg/dL, Perempuan &le;6.0 mg/dL<br>
                                        • Tinggi: Laki &gt;7.0 mg/dL, Perempuan &gt;6.0 mg/dL
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-3 font-bold text-gray-900">IMT (BB / TB²)</td>
                                    <td class="px-4 py-3 font-mono font-bold text-green-700">18.5 - 25.0</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        • Kurus: &lt;18.5<br>
                                        • Normal: 18.5 - 25.0<br>
                                        • Gemuk (Overweight): 25.1 - 27.0<br>
                                        • Obesitas: &gt;27.0
                                    </td>
                                </tr>
                                <tr class="bg-red-50/20">
                                    <td class="px-4 py-3 font-bold text-gray-900">Lingkar Perut (Obesitas Sentral)</td>
                                    <td class="px-4 py-3 font-mono font-bold text-green-700">L &le; 90 / P &le; 80 cm</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        • Normal: Laki-laki &le;90 cm, Perempuan &le;80 cm<br>
                                        • Obesitas Sentral: Laki-laki &gt;90 cm, Perempuan &gt;80 cm
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center rounded-b-3xl">
                    <span class="text-[11px] text-gray-500">Sumber: Buku Panduan Posyandu Lansia & PTM Kemenkes RI</span>
                    <button type="button" onclick="closeLansiaRefModal()" class="px-5 py-2 bg-red-600 text-white font-bold text-xs rounded-xl shadow-md">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openLansiaRefModal() {
            document.getElementById('lansia-ref-modal').classList.remove('hidden');
        }

        function closeLansiaRefModal() {
            document.getElementById('lansia-ref-modal').classList.add('hidden');
        }

        function openEditLansiaExamModal(exam) {
            const form = document.getElementById('edit-lansia-exam-form');
            form.action = `/pemeriksaan-lansias/${exam.id}`;

            document.getElementById('edit_tanggal_pemeriksaan').value = exam.tanggal_pemeriksaan || '';
            document.getElementById('edit_berat_badan').value = exam.berat_badan || '';
            document.getElementById('edit_tinggi_badan').value = exam.tinggi_badan || '';
            document.getElementById('edit_lingkar_perut').value = exam.lingkar_perut || '';
            document.getElementById('edit_tensi_sistolik').value = exam.tensi_sistolik || '';
            document.getElementById('edit_tensi_diastolik').value = exam.tensi_diastolik || '';
            document.getElementById('edit_gula_darah').value = exam.gula_darah || '';
            document.getElementById('edit_jenis_gula_darah').value = exam.jenis_gula_darah || 'sewaktu';
            document.getElementById('edit_kolesterol').value = exam.kolesterol || '';
            document.getElementById('edit_asam_urat').value = exam.asam_urat || '';
            document.getElementById('edit_keluhan').value = exam.keluhan || '';
            document.getElementById('edit_catatan').value = exam.catatan || '';

            document.getElementById('edit-lansia-exam-modal').classList.remove('hidden');
        }

        function closeEditLansiaExamModal() {
            document.getElementById('edit-lansia-exam-modal').classList.add('hidden');
        }

        function updateLansiaSort(field) {
            const form = document.getElementById('lansia-history-filter-form');
            if (!form) return;
            const currentField = form.sort.value;
            const currentDirection = form.direction.value;

            if (currentField === field) {
                form.direction.value = currentDirection === 'asc' ? 'desc' : 'asc';
            } else {
                form.sort.value = field;
                form.direction.value = 'desc';
            }
            form.submit();
        }

        let lansiaSearchTimeout;
        const lansiaSearchInput = document.getElementById('lansia-history-search');
        if (lansiaSearchInput) {
            lansiaSearchInput.addEventListener('input', function() {
                clearTimeout(lansiaSearchTimeout);
                lansiaSearchTimeout = setTimeout(() => {
                    document.getElementById('lansia-history-filter-form').submit();
                }, 500);
            });
        }
    </script>
</x-layout>
