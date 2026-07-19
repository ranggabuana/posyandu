<x-layout>
    <x-slot:title>Pemeriksaan Kesehatan Remaja - Posyandu Banjar</x-slot:title>

    <x-page-header 
        title="Pemeriksaan Kesehatan Remaja (PKPR)"
        subtitle="Catat hasil pemeriksaan fisik, skrining anemia, KEK & rekam medis remaja"
        icon="mdi-heart-pulse"
        :breadcrumbs="[
            'Data Kesehatan' => null,
            'Data Remaja' => route('remajas.index'),
            'Pemeriksaan' => null
        ]"
    >
        <button type="button" onclick="openRefModal()" 
            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200 font-semibold rounded-xl transition text-xs shadow-2xs">
            <i class="mdi mdi-information-outline text-sm"></i>
            Detail Standar Medis Kemenkes RI
        </button>
        <a href="{{ route('remajas.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold transition-all shadow-2xs">
            <i class="mdi mdi-arrow-left text-sm"></i> Kembali
        </a>
    </x-page-header>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold">
                    <i class="mdi mdi-check text-lg"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm">Berhasil!</h4>
                    <p class="text-xs text-emerald-600">{{ session('success') }}</p>
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="mdi mdi-close"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Sidebar Identitas Remaja -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Profil Card Remaja -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100/50 to-indigo-100/30 rounded-bl-full -z-0"></div>
                
                <div class="relative z-10">
                    @php
                        $isLaki = in_array(strtolower($remaja->penduduk->kelamin ?? ''), ['laki-laki', 'l']);
                    @endphp
                    <div class="mb-5">
                        <span class="px-2.5 py-0.5 {{ $isLaki ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-pink-50 text-pink-700 border-pink-100' }} font-bold text-[11px] rounded-full border inline-flex items-center gap-1">
                            {{ $isLaki ? 'Laki-Laki' : 'Perempuan' }} ({{ $remaja->penduduk->umur ?? $remaja->penduduk->usia ?? '-' }} Thn)
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 mt-1 leading-snug">
                            {{ $remaja->penduduk->nama }}
                        </h3>
                        <p class="text-xs text-gray-500 font-mono">NIK: {{ $remaja->penduduk->nik }}</p>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-gray-100 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Tanggal Lahir:</span>
                            <span class="font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($remaja->penduduk->tanggallahir)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Alamat / Wilayah:</span>
                            <span class="font-semibold text-gray-800 text-right">
                                {{ $remaja->penduduk->dusun ?? '-' }} (RW {{ $remaja->penduduk->rw ?? '00' }}/RT {{ $remaja->penduduk->rt ?? '00' }})
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">No. Telepon / WA:</span>
                            <span class="font-semibold text-gray-800">{{ $remaja->penduduk->telepon ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Posyandu Binaan:</span>
                            <span class="font-semibold text-blue-600">{{ $remaja->posyandu->nama ?? 'Posyandu Desa' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Input Pemeriksaan Remaja -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between pb-6 mb-6 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <i class="mdi mdi-clipboard-text-outline text-blue-600 text-xl"></i>
                            Input Hasil Skrining Kesehatan Remaja
                        </h3>
                        <p class="text-xs text-gray-500 mt-0.5">Parameter standar Kemenkes RI (Posyandu Remaja PKPR)</p>
                    </div>
                    <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-semibold border border-blue-100">
                        Pemeriksaan Baru
                    </span>
                </div>

                <form action="{{ route('remajas.store-pemeriksaan', $remaja->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Tanggal Pemeriksaan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Tanggal Pemeriksaan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_pemeriksaan" value="{{ old('tanggal_pemeriksaan', date('Y-m-d')) }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                                Pemberian Tablet Tambah Darah (TTD) <span class="text-red-500">*</span>
                            </label>
                            <select name="pemberian_ttd" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition outline-none">
                                <option value="tidak" {{ old('pemberian_ttd') == 'tidak' ? 'selected' : '' }}>Tidak Diberikan</option>
                                <option value="ya" {{ old('pemberian_ttd') == 'ya' ? 'selected' : '' }}>Ya, Diberikan Tablet TTD</option>
                            </select>
                        </div>
                    </div>

                    <!-- Fisik & Antropometri -->
                    <div class="pt-4 border-t border-gray-100">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                            <i class="mdi mdi-ruler"></i> Pengukuran Fisik & Antropometri
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Berat Badan (kg)</label>
                                <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan') }}" placeholder="Contoh: 48.5"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:bg-white focus:border-blue-500 transition outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Tinggi Badan (cm)</label>
                                <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan') }}" placeholder="Contoh: 158.0"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:bg-white focus:border-blue-500 transition outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Lingkar Perut (cm)</label>
                                <input type="number" step="0.1" name="lingkar_perut" value="{{ old('lingkar_perut') }}" placeholder="Contoh: 70"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:bg-white focus:border-blue-500 transition outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Skrining KEK & Anemia -->
                    <div class="pt-4 border-t border-gray-100">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                            <i class="mdi mdi-needle"></i> Skrining KEK (Kurang Energi Kronis) & Anemia
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    LiLA / Lingkar Lengan Atas (cm)
                                </label>
                                <input type="number" step="0.1" name="lila" value="{{ old('lila') }}" placeholder="Normal >= 23.5 cm"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:bg-white focus:border-blue-500 transition outline-none">
                                <p class="text-[10px] text-gray-400 mt-1">LiLA < 23.5 cm terindikasi Resiko KEK</p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">
                                    Hemoglobin / Kadar HB (g/dL)
                                </label>
                                <input type="number" step="0.1" name="hemoglobin" value="{{ old('hemoglobin') }}" placeholder="Normal >= 12.0 g/dL"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:bg-white focus:border-blue-500 transition outline-none">
                                <p class="text-[10px] text-gray-400 mt-1">Skrining Anemia (Normal >= 12 g/dL)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Vital & Gula Darah -->
                    <div class="pt-4 border-t border-gray-100">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                            <i class="mdi mdi-heart-pulse"></i> Vital Sign & Pemeriksaan Darah
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Tensi Sistolik (mmHg)</label>
                                <input type="number" name="tensi_sistolik" value="{{ old('tensi_sistolik') }}" placeholder="Contoh: 110"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:bg-white focus:border-blue-500 transition outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Tensi Diastolik (mmHg)</label>
                                <input type="number" name="tensi_diastolik" value="{{ old('tensi_diastolik') }}" placeholder="Contoh: 70"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:bg-white focus:border-blue-500 transition outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Gula Darah (mg/dL)</label>
                                <div class="flex gap-2">
                                    <input type="number" name="gula_darah" value="{{ old('gula_darah') }}" placeholder="mg/dL"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:bg-white focus:border-blue-500 transition outline-none">
                                    <select name="jenis_gula_darah" class="bg-gray-50 border border-gray-200 rounded-xl px-2 py-2 text-xs focus:bg-white outline-none">
                                        <option value="sewaktu" {{ old('jenis_gula_darah') == 'sewaktu' ? 'selected' : '' }}>Sewaktu</option>
                                        <option value="puasa" {{ old('jenis_gula_darah') == 'puasa' ? 'selected' : '' }}>Puasa</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keluhan & Catatan -->
                    <div class="pt-4 border-t border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Keluhan Kesehatan / Anamnesa</label>
                            <textarea name="keluhan" rows="2" placeholder="Catat keluhan fisik/pusing/lemas jika ada..."
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:bg-white focus:border-blue-500 transition outline-none resize-none">{{ old('keluhan') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan & Konseling Kader/Bidan</label>
                            <textarea name="catatan" rows="2" placeholder="Nasihat gizi, pola tidur, kebersihan diri..."
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 text-sm focus:bg-white focus:border-blue-500 transition outline-none resize-none">{{ old('catatan') }}</textarea>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition flex items-center gap-2 text-sm">
                            <i class="mdi mdi-content-save"></i>
                            Simpan Hasil Pemeriksaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Riwayat Pemeriksaan Remaja -->
    <div class="mt-8 bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 mb-6 border-b border-gray-100">
            <div>
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <i class="mdi mdi-history text-blue-600 text-xl"></i>
                    Riwayat Skrining & Pemeriksaan Remaja
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">Rekam medis historis pemeriksaan kesehatan berkala</p>
            </div>

            <!-- Filter & Search Riwayat -->
            <form action="{{ route('remajas.pemeriksaan', $remaja->id) }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative min-w-[200px]">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="mdi mdi-magnify text-lg"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari keluhan / status..."
                        class="w-full pl-9 pr-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:bg-white focus:border-blue-500 outline-none">
                </div>

                <select name="per_page" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 text-xs outline-none">
                    @foreach([5, 10, 25, 50] as $size)
                        <option value="{{ $size }}" {{ request('per_page', 5) == $size ? 'selected' : '' }}>{{ $size }} data</option>
                    @endforeach
                </select>

                <input type="hidden" name="sort" value="{{ request('sort', 'tanggal_pemeriksaan') }}">
                <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="py-3.5 px-4 font-bold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="py-3.5 px-4 font-bold text-gray-600 uppercase tracking-wider">BB / TB / IMT</th>
                        <th class="py-3.5 px-4 font-bold text-gray-600 uppercase tracking-wider">LiLA (KEK)</th>
                        <th class="py-3.5 px-4 font-bold text-gray-600 uppercase tracking-wider">HB (Anemia)</th>
                        <th class="py-3.5 px-4 font-bold text-gray-600 uppercase tracking-wider">Tensi & Gula</th>
                        <th class="py-3.5 px-4 font-bold text-gray-600 uppercase tracking-wider">TTD</th>
                        <th class="py-3.5 px-4 font-bold text-gray-600 uppercase tracking-wider">Keluhan & Catatan</th>
                        <th class="py-3.5 px-4 font-bold text-gray-600 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pemeriksaanHistory as $exam)
                        <tr class="hover:bg-blue-50/20 transition-colors">
                            <td class="py-4 px-4 font-semibold text-gray-800 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($exam->tanggal_pemeriksaan)->translatedFormat('d M Y') }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="text-gray-900 font-bold">
                                    {{ $exam->berat_badan ? $exam->berat_badan . ' kg' : '-' }} / {{ $exam->tinggi_badan ? $exam->tinggi_badan . ' cm' : '-' }}
                                </div>
                                @if($exam->imt)
                                    <div class="mt-1 flex items-center gap-1.5">
                                        <span class="font-mono text-[10px] text-gray-500">IMT: {{ $exam->imt }}</span>
                                        <span class="px-2 py-0.5 rounded-full font-bold text-[10px] 
                                            {{ $exam->status_imt === 'Normal' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                            {{ $exam->status_imt }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="font-semibold text-gray-800">{{ $exam->lila ? $exam->lila . ' cm' : '-' }}</div>
                                @if($exam->status_lila)
                                    <span class="mt-1 inline-block px-2 py-0.5 rounded-full font-bold text-[10px]
                                        {{ $exam->status_lila === 'Normal' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                        {{ $exam->status_lila }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="font-semibold text-gray-800">{{ $exam->hemoglobin ? $exam->hemoglobin . ' g/dL' : '-' }}</div>
                                @if($exam->status_hb)
                                    <span class="mt-1 inline-block px-2 py-0.5 rounded-full font-bold text-[10px]
                                        {{ $exam->status_hb === 'Normal' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                        {{ $exam->status_hb }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="text-xs text-gray-800">
                                    Tensi: {{ ($exam->tensi_sistolik && $exam->tensi_diastolik) ? $exam->tensi_sistolik.'/'.$exam->tensi_diastolik.' mmHg' : '-' }}
                                </div>
                                <div class="text-xs text-gray-800 mt-0.5">
                                    Gula: {{ $exam->gula_darah ? $exam->gula_darah . ' mg/dL (' . $exam->jenis_gula_darah . ')' : '-' }}
                                </div>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full font-bold text-[10px] {{ $exam->pemberian_ttd === 'ya' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $exam->pemberian_ttd === 'ya' ? 'Diberikan TTD' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 max-w-xs">
                                <div class="text-xs text-gray-800 truncate" title="{{ $exam->keluhan }}">{{ $exam->keluhan ? 'Keluhan: '.$exam->keluhan : '-' }}</div>
                                <div class="text-[11px] text-gray-500 truncate" title="{{ $exam->catatan }}">{{ $exam->catatan ? 'Catatan: '.$exam->catatan : '-' }}</div>
                            </td>
                            <td class="py-4 px-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" onclick="openEditModal({{ json_encode($exam) }})"
                                        class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition">
                                        <i class="mdi mdi-pencil text-sm"></i>
                                    </button>
                                    <button type="button" onclick="confirmDeleteExam({{ $exam->id }})"
                                        class="p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition">
                                        <i class="mdi mdi-trash-can-outline text-sm"></i>
                                    </button>
                                </div>
                                <form id="delete-exam-form-{{ $exam->id }}" action="{{ route('pemeriksaan-remajas.destroy', $exam->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-gray-400">
                                <i class="mdi mdi-clipboard-text-off-outline text-4xl block mb-2"></i>
                                Belum ada data riwayat pemeriksaan kesehatan untuk remaja ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pemeriksaanHistory->hasPages())
            <div class="pt-4 border-t border-gray-100">
                {{ $pemeriksaanHistory->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Detail Standar Medis Remaja (Kemenkes RI) -->
    <div id="remaja-ref-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 md:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <button onclick="closeRefModal()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
                <i class="mdi mdi-close text-xl"></i>
            </button>

            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <i class="mdi mdi-medical-bag text-xl"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Batas Normal & Standar Skrining Remaja (Kemenkes RI)</h3>
                    <p class="text-xs text-gray-500">Pedoman Pelayanan Kesehatan Peduli Remaja (PKPR)</p>
                </div>
            </div>

            <div class="space-y-4 text-xs">
                <!-- Status IMT Remaja -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-1.5">
                        <i class="mdi mdi-scale"></i> Indeks Massa Tubuh (IMT) Remaja:
                    </h4>
                    <ul class="space-y-1 text-gray-600 pl-4 list-disc">
                        <li><strong>< 17.0</strong> : Sangat Kurus</li>
                        <li><strong>17.0 - 18.4</strong> : Kurus</li>
                        <li><strong>18.5 - 25.0</strong> : Normal (Gizi Baik)</li>
                        <li><strong>25.1 - 27.0</strong> : Gemuk (Kelebihan BB)</li>
                        <li><strong>> 27.0</strong> : Obesitas</li>
                    </ul>
                </div>

                <!-- Skrining KEK & Anemia -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-1.5">
                        <i class="mdi mdi-needle"></i> Skrining KEK & Anemia Remaja Putri:
                    </h4>
                    <ul class="space-y-1 text-gray-600 pl-4 list-disc">
                        <li><strong>LiLA (Lingkar Lengan Atas) < 23.5 cm</strong> : Terindikasi Resiko KEK (Kurang Energi Kronis)</li>
                        <li><strong>Hemoglobin (HB) < 8.0 g/dL</strong> : Anemia Berat</li>
                        <li><strong>Hemoglobin (HB) 8.0 - 10.9 g/dL</strong> : Anemia Sedang</li>
                        <li><strong>Hemoglobin (HB) 11.0 - 11.9 g/dL</strong> : Anemia Ringan</li>
                        <li><strong>Hemoglobin (HB) >= 12.0 g/dL</strong> : Normal (Tidak Anemia)</li>
                    </ul>
                </div>

                <!-- Tekanan Darah -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-1.5">
                        <i class="mdi mdi-heart-pulse"></i> Tekanan Darah (Tensi Remaja):
                    </h4>
                    <ul class="space-y-1 text-gray-600 pl-4 list-disc">
                        <li><strong>Normal</strong> : Sistolik < 120 mmHg DAN Diastolik < 80 mmHg</li>
                        <li><strong>Pre-Hipertensi</strong> : Sistolik 120-139 mmHg ATAU Diastolik 80-89 mmHg</li>
                        <li><strong>Hipertensi</strong> : Sistolik >= 140 mmHg ATAU Diastolik >= 90 mmHg</li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button onclick="closeRefModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl text-xs hover:bg-gray-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Edit Pemeriksaan Remaja -->
    <div id="edit-exam-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 md:p-8 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <button onclick="closeEditModal()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600">
                <i class="mdi mdi-close text-xl"></i>
            </button>

            <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="mdi mdi-pencil text-blue-600"></i>
                Edit Rekam Pemeriksaan Remaja
            </h3>

            <form id="edit-exam-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Pemeriksaan</label>
                        <input type="date" id="edit_tanggal_pemeriksaan" name="tanggal_pemeriksaan" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Pemberian Tablet Tambah Darah (TTD)</label>
                        <select id="edit_pemberian_ttd" name="pemberian_ttd" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs outline-none">
                            <option value="tidak">Tidak Diberikan</option>
                            <option value="ya">Ya, Diberikan Tablet TTD</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">BB (kg)</label>
                        <input type="number" step="0.1" id="edit_berat_badan" name="berat_badan" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">TB (cm)</label>
                        <input type="number" step="0.1" id="edit_tinggi_badan" name="tinggi_badan" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Lingkar Perut (cm)</label>
                        <input type="number" step="0.1" id="edit_lingkar_perut" name="lingkar_perut" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">LiLA (cm)</label>
                        <input type="number" step="0.1" id="edit_lila" name="lila" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Hemoglobin / HB (g/dL)</label>
                        <input type="number" step="0.1" id="edit_hemoglobin" name="hemoglobin" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Sistolik</label>
                        <input type="number" id="edit_tensi_sistolik" name="tensi_sistolik" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Diastolik</label>
                        <input type="number" id="edit_tensi_diastolik" name="tensi_diastolik" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Gula Darah</label>
                        <input type="number" id="edit_gula_darah" name="gula_darah" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs outline-none">
                        <select id="edit_jenis_gula_darah" name="jenis_gula_darah" class="w-full mt-1 bg-gray-50 border border-gray-200 rounded-xl px-2 py-1 text-[11px] outline-none">
                            <option value="sewaktu">Sewaktu</option>
                            <option value="puasa">Puasa</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Keluhan</label>
                        <textarea id="edit_keluhan" name="keluhan" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan</label>
                        <textarea id="edit_catatan" name="catatan" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs outline-none"></textarea>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-xs font-bold hover:bg-gray-200">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openRefModal() {
            document.getElementById('remaja-ref-modal').classList.remove('hidden');
        }
        function closeRefModal() {
            document.getElementById('remaja-ref-modal').classList.add('hidden');
        }

        function openEditModal(exam) {
            const form = document.getElementById('edit-exam-form');
            form.action = `/pemeriksaan-remajas/${exam.id}`;
            document.getElementById('edit_tanggal_pemeriksaan').value = exam.tanggal_pemeriksaan || '';
            document.getElementById('edit_pemberian_ttd').value = exam.pemberian_ttd || 'tidak';
            document.getElementById('edit_berat_badan').value = exam.berat_badan || '';
            document.getElementById('edit_tinggi_badan').value = exam.tinggi_badan || '';
            document.getElementById('edit_lingkar_perut').value = exam.lingkar_perut || '';
            document.getElementById('edit_lila').value = exam.lila || '';
            document.getElementById('edit_hemoglobin').value = exam.hemoglobin || '';
            document.getElementById('edit_tensi_sistolik').value = exam.tensi_sistolik || '';
            document.getElementById('edit_tensi_diastolik').value = exam.tensi_diastolik || '';
            document.getElementById('edit_gula_darah').value = exam.gula_darah || '';
            document.getElementById('edit_jenis_gula_darah').value = exam.jenis_gula_darah || 'sewaktu';
            document.getElementById('edit_keluhan').value = exam.keluhan || '';
            document.getElementById('edit_catatan').value = exam.catatan || '';

            document.getElementById('edit-exam-modal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-exam-modal').classList.add('hidden');
        }

        function confirmDeleteExam(id) {
            Swal.fire({
                title: 'Hapus Rekam Pemeriksaan?',
                text: 'Data riwayat pemeriksaan remaja ini akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-exam-form-${id}`).submit();
                }
            });
        }
    </script>
    @endpush
</x-layout>
