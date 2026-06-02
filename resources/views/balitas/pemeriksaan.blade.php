<x-layout title="Pemeriksaan Balita Lebih Lanjut">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pemeriksaan Balita Lebih Lanjut</h2>
            <p class="text-gray-500 mt-1">Catat hasil pemeriksaan perkembangan untuk <strong>{{ $balita->bayiBalita->penduduk->nama }}</strong> (NIK: {{ $balita->bayiBalita->penduduk->nik }}).</p>
        </div>
        <a href="{{ route('balitas.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('balitas.update-pemeriksaan', $balita) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Vitamin, Immunization, and Monthly Weights -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Card 1: Akta, Vitamin A & Imunisasi Booster -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-green-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-needle text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Administrasi, Vitamin A & Imunisasi Booster</h3>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <!-- Akta Kelahiran -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Status Akta Kelahiran <span class="text-red-500">*</span></label>
                                <select name="status_akta" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="punya" {{ old('status_akta', $balita->status_akta) == 'punya' ? 'selected' : '' }}>Punya</option>
                                    <option value="tidak punya" {{ old('status_akta', $balita->status_akta) == 'tidak punya' ? 'selected' : '' }}>Tidak Punya</option>
                                </select>
                                @error('status_akta') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Vitamin A Section -->
                        <div class="pt-6 border-t border-gray-100">
                            <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="mdi mdi-pill text-orange-500 text-lg"></i>
                                Pemberian Vitamin A
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                @foreach([18, 24, 30, 36, 42, 48, 54, 60] as $month)
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1.5">{{ $month }} Bulan</label>
                                        <select name="vitamin_a_{{ $month }}" 
                                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:0.75rem_0.75rem] bg-[right_0.5rem_center] bg-no-repeat">
                                            <option value="belum" {{ old('vitamin_a_' . $month, $balita->{'vitamin_a_' . $month}) == 'belum' ? 'selected' : '' }}>Belum</option>
                                            <option value="sudah" {{ old('vitamin_a_' . $month, $balita->{'vitamin_a_' . $month}) == 'sudah' ? 'selected' : '' }}>Sudah</option>
                                        </select>
                                        @error('vitamin_a_' . $month) <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p> @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Booster Immunization Section -->
                        <div class="pt-6 border-t border-gray-100">
                            <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="mdi mdi-needle text-purple-500 text-lg"></i>
                                Imunisasi Booster / Ulang
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">DPT-Hb-Hib (18-36 Bulan)</label>
                                    <select name="booster_dpt_hb_hib" 
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                        <option value="belum" {{ old('booster_dpt_hb_hib', $balita->booster_dpt_hb_hib) == 'belum' ? 'selected' : '' }}>Belum</option>
                                        <option value="sudah" {{ old('booster_dpt_hb_hib', $balita->booster_dpt_hb_hib) == 'sudah' ? 'selected' : '' }}>Sudah</option>
                                    </select>
                                    @error('booster_dpt_hb_hib') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Campak (24-36 Bulan)</label>
                                    <select name="booster_campak" 
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                        <option value="belum" {{ old('booster_campak', $balita->booster_campak) == 'belum' ? 'selected' : '' }}>Belum</option>
                                        <option value="sudah" {{ old('booster_campak', $balita->booster_campak) == 'sudah' ? 'selected' : '' }}>Sudah</option>
                                    </select>
                                    @error('booster_campak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Keterangan Balita -->
                        <div class="pt-6 border-t border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan Balita</label>
                            <input type="text" name="keterangan_balita" value="{{ old('keterangan_balita', $balita->keterangan_balita) }}" placeholder="Catatan tambahan mengenai balita..."
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all duration-200">
                            @error('keterangan_balita') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Pemantauan Timbangan Berat Badan (Tahun 2 s.d 5) -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-scale-bathroom text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Timbangan Berat Badan (kg)</h3>
                        </div>
                    </div>
                    <div class="p-8 space-y-8">
                        
                        <!-- Tahun Ke-2 -->
                        <div>
                            <h4 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-50 flex items-center gap-1.5">
                                <i class="mdi mdi-numeric-2-box"></i> Tahun Ke-2 (Bulan 13 - 24)
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @for($i = 13; $i <= 24; $i++)
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Bulan {{ $i }}</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" name="bb_bulan_{{ $i }}" 
                                                value="{{ old('bb_bulan_' . $i, $balita->{'bb_bulan_' . $i}) }}" placeholder="0.00" 
                                                class="w-full pl-3 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-semibold text-gray-400">kg</span>
                                        </div>
                                        @error('bb_bulan_' . $i) <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p> @enderror
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Tahun Ke-3 -->
                        <div>
                            <h4 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-50 flex items-center gap-1.5">
                                <i class="mdi mdi-numeric-3-box"></i> Tahun Ke-3 (Bulan 25 - 36)
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @for($i = 25; $i <= 36; $i++)
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Bulan {{ $i }}</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" name="bb_bulan_{{ $i }}" 
                                                value="{{ old('bb_bulan_' . $i, $balita->{'bb_bulan_' . $i}) }}" placeholder="0.00" 
                                                class="w-full pl-3 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-semibold text-gray-400">kg</span>
                                        </div>
                                        @error('bb_bulan_' . $i) <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p> @enderror
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Tahun Ke-4 -->
                        <div>
                            <h4 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-50 flex items-center gap-1.5">
                                <i class="mdi mdi-numeric-4-box"></i> Tahun Ke-4 (Bulan 37 - 48)
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @for($i = 37; $i <= 48; $i++)
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Bulan {{ $i }}</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" name="bb_bulan_{{ $i }}" 
                                                value="{{ old('bb_bulan_' . $i, $balita->{'bb_bulan_' . $i}) }}" placeholder="0.00" 
                                                class="w-full pl-3 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-semibold text-gray-400">kg</span>
                                        </div>
                                        @error('bb_bulan_' . $i) <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p> @enderror
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Tahun Ke-5 -->
                        <div>
                            <h4 class="text-sm font-bold text-blue-600 mb-4 pb-2 border-b border-blue-50 flex items-center gap-1.5">
                                <i class="mdi mdi-numeric-5-box"></i> Tahun Ke-5 (Bulan 49 - 60)
                            </h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @for($i = 49; $i <= 60; $i++)
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Bulan {{ $i }}</label>
                                        <div class="relative">
                                            <input type="number" step="0.01" name="bb_bulan_{{ $i }}" 
                                                value="{{ old('bb_bulan_' . $i, $balita->{'bb_bulan_' . $i}) }}" placeholder="0.00" 
                                                class="w-full pl-3 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-semibold text-gray-400">kg</span>
                                        </div>
                                        @error('bb_bulan_' . $i) <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p> @enderror
                                    </div>
                                @endfor
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Side: Child Profile Card & Action Buttons -->
            <div class="lg:col-span-4 space-y-8">
                
                <!-- Profile Summary Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-500/10 rounded-lg mr-3">
                                <i class="mdi mdi-account-card-details text-blue-600"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">Profil Balita</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-4 text-sm text-gray-600">
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="font-semibold">Nama Anak:</span>
                            <span class="text-gray-900 font-bold text-right">{{ $balita->bayiBalita->penduduk->nama }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="font-semibold">NIK:</span>
                            <span class="text-gray-900 font-mono">{{ $balita->bayiBalita->penduduk->nik }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="font-semibold">Tanggal Lahir:</span>
                            <span class="text-gray-900">{{ \Carbon\Carbon::parse($balita->bayiBalita->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="font-semibold">Jenis Kelamin:</span>
                            <span class="text-gray-900 capitalize">{{ $balita->bayiBalita->penduduk->kelamin }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="font-semibold">Nama Ibu:</span>
                            <span class="text-gray-900">{{ $balita->bayiBalita->nama_ibu ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="font-semibold">Posyandu:</span>
                            <span class="text-gray-900 font-bold">{{ $balita->bayiBalita->posyandu->nama ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="mdi mdi-content-save text-xl"></i>
                        Simpan Pemeriksaan
                    </button>
                    <a href="{{ route('balitas.index') }}" class="w-full mt-4 bg-white text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-layout>
