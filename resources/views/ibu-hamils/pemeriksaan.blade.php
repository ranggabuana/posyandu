<x-layout title="Pemeriksaan Ibu Hamil">
    <x-page-header 
        title="Pemeriksaan Ibu Hamil"
        subtitle="Catat hasil pemeriksaan untuk Ibu <strong>{{ $ibuHamil->penduduk->nama }}</strong> (NIK: {{ $ibuHamil->penduduk->nik }})"
        icon="mdi-human-pregnant"
        :breadcrumbs="[
            'Data Kesehatan' => null,
            'Ibu Hamil' => route('ibu-hamils.index'),
            'Pemeriksaan' => null
        ]"
    >
        <a href="{{ route('ibu-hamils.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold transition-all shadow-2xs">
            <i class="mdi mdi-arrow-left text-sm"></i> Kembali
        </a>
    </x-page-header>

    <form action="{{ route('ibu-hamils.update-pemeriksaan', $ibuHamil) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Vitamin & Weight Monitoring -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Card 1: Vitamin & Imunisasi -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-4 sm:px-8 py-4 sm:py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-pink-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-pill text-pink-600 text-xl"></i>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-800">Vitamin & Imunisasi</h3>
                        </div>
                    </div>
                    <div class="p-4 sm:p-8 space-y-6">
                        <!-- Imunisasi Date Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Imunisasi TT3</label>
                                <input type="date" name="imunisasi_tt3" value="{{ old('imunisasi_tt3', $ibuHamil->imunisasi_tt3) }}" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 outline-none transition-all duration-200">
                                @error('imunisasi_tt3') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Imunisasi TT+4</label>
                                <input type="date" name="imunisasi_tt4" value="{{ old('imunisasi_tt4', $ibuHamil->imunisasi_tt4) }}" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 outline-none transition-all duration-200">
                                @error('imunisasi_tt4') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Imunisasi TT+5</label>
                                <input type="date" name="imunisasi_tt5" value="{{ old('imunisasi_tt5', $ibuHamil->imunisasi_tt5) }}" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 outline-none transition-all duration-200">
                                @error('imunisasi_tt5') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Tablet Tambah Darah Selects -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-100">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tablet Tambah Darah 1</label>
                                <select name="tablet_tambah_darah_1" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="Belum" {{ old('tablet_tambah_darah_1', $ibuHamil->tablet_tambah_darah_1) == 'Belum' ? 'selected' : '' }}>Belum</option>
                                    <option value="Sudah" {{ old('tablet_tambah_darah_1', $ibuHamil->tablet_tambah_darah_1) == 'Sudah' ? 'selected' : '' }}>Sudah</option>
                                </select>
                                @error('tablet_tambah_darah_1') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tablet Tambah Darah 2</label>
                                <select name="tablet_tambah_darah_2" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="Belum" {{ old('tablet_tambah_darah_2', $ibuHamil->tablet_tambah_darah_2) == 'Belum' ? 'selected' : '' }}>Belum</option>
                                    <option value="Sudah" {{ old('tablet_tambah_darah_2', $ibuHamil->tablet_tambah_darah_2) == 'Sudah' ? 'selected' : '' }}>Sudah</option>
                                </select>
                                @error('tablet_tambah_darah_2') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tablet Tambah Darah 3</label>
                                <select name="tablet_tambah_darah_3" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="Belum" {{ old('tablet_tambah_darah_3', $ibuHamil->tablet_tambah_darah_3) == 'Belum' ? 'selected' : '' }}>Belum</option>
                                    <option value="Sudah" {{ old('tablet_tambah_darah_3', $ibuHamil->tablet_tambah_darah_3) == 'Sudah' ? 'selected' : '' }}>Sudah</option>
                                </select>
                                @error('tablet_tambah_darah_3') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Pemantauan Berat Badan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-scale-bathroom text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Pemantauan Berat Badan (Setiap Bulan)</h3>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @for($i = 1; $i <= 12; $i++)
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Bulan Ke-{{ $i }} (kg)</label>
                                    <div class="relative">
                                        <input type="number" step="0.01" name="bb_bulan_{{ $i }}" 
                                            value="{{ old('bb_bulan_' . $i, $ibuHamil->{'bb_bulan_' . $i}) }}" placeholder="0.00" 
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

            <!-- Right Side: Delivery Notes & Action Buttons -->
            <div class="lg:col-span-4 space-y-8">
                
                <!-- Card 3: Keterangan Persalinan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2 bg-rose-500/10 rounded-lg mr-3">
                                <i class="mdi mdi-baby-carriage text-rose-600"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">Keterangan Persalinan</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Melahirkan</label>
                            <input type="date" name="tgl_melahirkan" value="{{ old('tgl_melahirkan', $ibuHamil->tgl_melahirkan) }}" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition-all duration-200">
                            @error('tgl_melahirkan') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ditolong Oleh</label>
                            <input type="text" name="ditolong_oleh" value="{{ old('ditolong_oleh', $ibuHamil->ditolong_oleh) }}" placeholder="Contoh: Bidan, Dokter" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition-all duration-200">
                            @error('ditolong_oleh') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Berat Badan Bayi (kg)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="bb_bayi" value="{{ old('bb_bayi', $ibuHamil->bb_bayi) }}" placeholder="0.00" 
                                    class="w-full pl-4 pr-12 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition-all duration-200">
                                <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-sm font-semibold text-gray-400">
                                    kg
                                </span>
                            </div>
                            @error('bb_bayi') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin Bayi</label>
                            <select name="jk_bayi" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                <option value="">Pilih...</option>
                                <option value="laki-laki" {{ old('jk_bayi', $ibuHamil->jk_bayi) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="perempuan" {{ old('jk_bayi', $ibuHamil->jk_bayi) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jk_bayi') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="mdi mdi-content-save text-xl"></i>
                        Simpan Pemeriksaan
                    </button>
                    <a href="{{ route('ibu-hamils.index') }}" class="w-full mt-4 bg-white text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-layout>
