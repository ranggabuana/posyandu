<x-layout title="Pemeriksaan Bayi & Balita">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pemeriksaan Bayi & Balita</h2>
            <p class="text-gray-500 mt-1">Catat dan lihat riwayat tumbuh kembang untuk <strong>{{ $bayiBalita->penduduk->nama }}</strong> (NIK: {{ $bayiBalita->penduduk->nik }}).</p>
        </div>
        <a href="{{ $bayiBalita->umur_bulan <= 12 ? route('bayi-balitas.index') : route('balitas.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Alert Success/Error -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-2">
            <i class="mdi mdi-check-circle text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Side: Forms (Pemeriksaan & Imunisasi) -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Card 1: Input Kunjungan / Penimbangan Bulanan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                            <i class="mdi mdi-scale-bathroom text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Catat Kunjungan & Hasil Timbangan</h3>
                    </div>
                </div>
                <div class="p-8">
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

            <!-- Card 2: Catat Imunisasi Baru -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-green-500/10 rounded-lg mr-4">
                            <i class="mdi mdi-needle text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Catat Imunisasi Baru</h3>
                    </div>
                </div>
                <div class="p-8">
                    <form action="{{ route('bayi-balitas.update-pemeriksaan', $bayiBalita) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Jenis Vaksin <span class="text-red-500">*</span></label>
                                <select name="imunisasi_nama_vaksin" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="" disabled selected>Pilih Vaksin...</option>
                                    @foreach($rekomendasiImunisasi as $vaksin)
                                        <option value="{{ $vaksin }}">{{ $vaksin }}</option>
                                    @endforeach
                                </select>
                                @error('imunisasi_nama_vaksin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pemberian <span class="text-red-500">*</span></label>
                                <input type="date" name="imunisasi_tanggal_pemberian" value="{{ old('imunisasi_tanggal_pemberian', date('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none">
                                @error('imunisasi_tanggal_pemberian') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan / Nomor Batch</label>
                                <input type="text" name="imunisasi_keterangan" placeholder="Contoh: Batch No. 9812A, reaksi demam ringan"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none">
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-green-500/15 transition-all flex items-center gap-2">
                                <i class="mdi mdi-needle"></i> Catat Imunisasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card 3: Riwayat Kunjungan / Pemeriksaan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-purple-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-history text-purple-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Riwayat Penimbangan Bulanan</h3>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Periksa</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Umur</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">BB (Kg) / TB (Cm)</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">LLA / LK (Cm)</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status Gizi (BB/U)</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Stunting (TB/U)</th>
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
                                        {{ $exam->berat_badan ?? '-' }} kg / {{ $exam->tinggi_badan ?? '-' }} cm
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $exam->lingkar_lengan_atas ?? '-' }} cm / {{ $exam->lingkar_kepala ?? '-' }} cm
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full 
                                            {{ $exam->status_gizi_bb_u == 'Normal' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                            {{ $exam->status_gizi_bb_u ?? 'Normal' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full 
                                            {{ $exam->status_gizi_tb_u == 'Normal' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                            {{ $exam->status_gizi_tb_u ?? 'Normal' }}
                                        </span>
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
                                        <form action="{{ route('pemeriksaans.destroy', $exam) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus riwayat pemeriksaan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                <i class="mdi mdi-delete text-lg"></i>
                                            </button>
                                        </form>
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
            </div>

        </div>

        <!-- Right Side: Profile Summary & Vaccine History -->
        <div class="lg:col-span-4 space-y-8">
            
            <!-- Profil Ringkas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
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

            <!-- Riwayat Vaksin Imunisasi -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-500/10 rounded-lg mr-3">
                            <i class="mdi mdi-needle text-green-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Riwayat Imunisasi</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="relative pl-6 border-l border-gray-100 space-y-6">
                        @forelse($imunisasiHistory as $imun)
                            <div class="relative">
                                <!-- Marker Bullet -->
                                <span class="absolute -left-[31px] top-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-green-100 border border-white">
                                    <span class="h-2 w-2 rounded-full bg-green-600"></span>
                                </span>
                                
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-bold text-sm text-gray-800">{{ $imun->nama_vaksin }}</p>
                                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($imun->tanggal_pemberian)->translatedFormat('d M Y') }}</p>
                                        @if($imun->keterangan)
                                            <p class="text-xs text-gray-500 italic mt-0.5">{{ $imun->keterangan }}</p>
                                        @endif
                                    </div>
                                    <form action="{{ route('imunisasis.destroy', $imun) }}" method="POST" onsubmit="return confirm('Hapus riwayat vaksin ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-400 text-sm">
                                <i class="mdi mdi-needle text-3xl opacity-20 block mb-1"></i>
                                Belum ada vaksin dicatat.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layout>
