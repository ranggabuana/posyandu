<x-layout title="Pemeriksaan Bayi & Balita">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pemeriksaan Bayi & Balita</h2>
            <p class="text-gray-500 mt-1">Catat hasil pemeriksaan dan perkembangan untuk <strong>{{ $bayiBalita->penduduk->nama }}</strong> (NIK: {{ $bayiBalita->penduduk->nik }}).</p>
        </div>
        <a href="{{ route('bayi-balitas.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('bayi-balitas.update-pemeriksaan', $bayiBalita) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Immunization & Weight Monitoring -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Card 1: Imunisasi -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-green-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-needle text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Catatan Imunisasi</h3>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">HBO < 7 Hari</label>
                                <select name="imunisasi_hbo_kurang_7_hari" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="">Pilih...</option>
                                    <option value="L" {{ old('imunisasi_hbo_kurang_7_hari', $bayiBalita->imunisasi_hbo_kurang_7_hari) == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="P" {{ old('imunisasi_hbo_kurang_7_hari', $bayiBalita->imunisasi_hbo_kurang_7_hari) == 'P' ? 'selected' : '' }}>P</option>
                                </select>
                                @error('imunisasi_hbo_kurang_7_hari') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">HBO > 7 Hari</label>
                                <select name="imunisasi_hbo_lebih_7_hari" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="">Pilih...</option>
                                    <option value="L" {{ old('imunisasi_hbo_lebih_7_hari', $bayiBalita->imunisasi_hbo_lebih_7_hari) == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="P" {{ old('imunisasi_hbo_lebih_7_hari', $bayiBalita->imunisasi_hbo_lebih_7_hari) == 'P' ? 'selected' : '' }}>P</option>
                                </select>
                                @error('imunisasi_hbo_lebih_7_hari') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">BCG & POLIO I</label>
                                <select name="imunisasi_bcg_polio1" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="">Pilih...</option>
                                    <option value="L" {{ old('imunisasi_bcg_polio1', $bayiBalita->imunisasi_bcg_polio1) == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="P" {{ old('imunisasi_bcg_polio1', $bayiBalita->imunisasi_bcg_polio1) == 'P' ? 'selected' : '' }}>P</option>
                                </select>
                                @error('imunisasi_bcg_polio1') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pentavalen 1 (DPT,Hb,Hib) Polio II</label>
                                <select name="imunisasi_pentavalen1_polio2" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="">Pilih...</option>
                                    <option value="L" {{ old('imunisasi_pentavalen1_polio2', $bayiBalita->imunisasi_pentavalen1_polio2) == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="P" {{ old('imunisasi_pentavalen1_polio2', $bayiBalita->imunisasi_pentavalen1_polio2) == 'P' ? 'selected' : '' }}>P</option>
                                </select>
                                @error('imunisasi_pentavalen1_polio2') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pentavalen 2 (DPT,Hb,Hib) Polio III</label>
                                <select name="imunisasi_pentavalen2_polio3" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="">Pilih...</option>
                                    <option value="L" {{ old('imunisasi_pentavalen2_polio3', $bayiBalita->imunisasi_pentavalen2_polio3) == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="P" {{ old('imunisasi_pentavalen2_polio3', $bayiBalita->imunisasi_pentavalen2_polio3) == 'P' ? 'selected' : '' }}>P</option>
                                </select>
                                @error('imunisasi_pentavalen2_polio3') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pentavalen 3 (DPT,Hb,Hib) Polio IV</label>
                                <select name="imunisasi_pentavalen3_polio4" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="">Pilih...</option>
                                    <option value="L" {{ old('imunisasi_pentavalen3_polio4', $bayiBalita->imunisasi_pentavalen3_polio4) == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="P" {{ old('imunisasi_pentavalen3_polio4', $bayiBalita->imunisasi_pentavalen3_polio4) == 'P' ? 'selected' : '' }}>P</option>
                                </select>
                                @error('imunisasi_pentavalen3_polio4') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan Imunisasi</label>
                            <input type="text" name="imunisasi_keterangan" value="{{ old('imunisasi_keterangan', $bayiBalita->imunisasi_keterangan) }}" placeholder="Catatan tambahan mengenai imunisasi"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all duration-200">
                            @error('imunisasi_keterangan') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Card 2: Pemantauan Timbangan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-scale-bathroom text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Timbangan Berat Badan (Rutin Setiap Bulan)</h3>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @for($i = 1; $i <= 12; $i++)
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Bulan Ke-{{ $i }} (kg)</label>
                                    <div class="relative">
                                        <input type="number" step="0.01" name="bb_bulan_{{ $i }}" 
                                            value="{{ old('bb_bulan_' . $i, $bayiBalita->{'bb_bulan_' . $i}) }}" placeholder="0.00" 
                                            class="w-full pl-4 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                        <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-sm font-semibold text-gray-400">
                                            kg
                                        </span>
                                    </div>
                                    @error('bb_bulan_' . $i) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            @endfor
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
                            <span class="font-semibold">Jenis Kelamin:</span>
                            <span class="text-gray-900 capitalize">{{ $bayiBalita->penduduk->kelamin }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-gray-50">
                            <span class="font-semibold">Nama Ibu:</span>
                            <span class="text-gray-900">{{ $bayiBalita->nama_ibu ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="font-semibold">Golongan Darah:</span>
                            <span class="text-gray-900 font-bold">{{ $bayiBalita->goldar ?? 'Tidak Tahu' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="mdi mdi-content-save text-xl"></i>
                        Simpan Pemeriksaan
                    </button>
                    <a href="{{ route('bayi-balitas.index') }}" class="w-full mt-4 bg-white text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-layout>
