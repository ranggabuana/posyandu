
<x-layout title="Tambah Ibu Hamil">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Data Ibu Hamil</h2>
            <p class="text-gray-500 mt-1">Lengkapi informasi kehamilan di bawah ini dengan benar.</p>
        </div>
        <a href="{{ route('ibu-hamils.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('ibu-hamils.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <!-- Card: Data Ibu -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-pink-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-face-woman text-pink-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Data Ibu</h3>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Penduduk (Ibu) <span class="text-red-500">*</span></label>
                            <select name="penduduk_id" id="select2-penduduk"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                <option></option>
                                @foreach($penduduks as $penduduk)
                                    <option value="{{ $penduduk->id }}" {{ old('penduduk_id') == $penduduk->id ? 'selected' : '' }}>
                                        {{ $penduduk->nama }} ({{ $penduduk->nik }})
                                    </option>
                                @endforeach
                            </select>
                            @error('penduduk_id') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Suami</label>
                            <select name="suami_id" id="select2-suami"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                <option></option>
                                @if(old('suami_id'))
                                    @php
                                        $oldSuami = \App\Models\Penduduk::find(old('suami_id'));
                                    @endphp
                                    @if($oldSuami)
                                        <option value="{{ $oldSuami->id }}" selected>{{ $oldSuami->nama }} ({{ $oldSuami->nik }})</option>
                                    @endif
                                @endif
                            </select>
                            @error('suami_id') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <script>
                    $(document).ready(function() {
                        $('#select2-penduduk').select2({
                            placeholder: 'Cari dan pilih nama Ibu...',
                            allowClear: true,
                            width: '100%',
                            language: {
                                noResults: function() {
                                    return "Data tidak ditemukan";
                                }
                            }
                        });

                        $('#select2-suami').select2({
                            placeholder: 'Pilih Suami (pilih Ibu terlebih dahulu)...',
                            allowClear: true,
                            width: '100%',
                            language: {
                                noResults: function() {
                                    return "Data tidak ditemukan";
                                }
                            }
                        });

                        function filterSuami(selectedSuamiId = null) {
                            var ibuId = $('#select2-penduduk').val();
                            
                            if (!ibuId) {
                                $('#select2-suami').empty().append('<option></option>').val(null).trigger('change');
                                return;
                            }

                            $.ajax({
                                url: '{{ route("ibu-hamils.get-suami") }}',
                                type: 'GET',
                                data: { ibu_id: ibuId },
                                success: function(data) {
                                    var selectSuami = $('#select2-suami');
                                    selectSuami.empty().append('<option></option>');
                                    
                                    $.each(data, function(index, item) {
                                        var selected = (selectedSuamiId && item.id == selectedSuamiId) ? 'selected' : '';
                                        selectSuami.append('<option value="' + item.id + '" ' + selected + '>' + item.nama + ' (' + item.nik + ')</option>');
                                    });
                                    
                                    selectSuami.trigger('change');
                                }
                            });
                        }

                        $('#select2-penduduk').on('change', function() {
                            filterSuami();
                        });

                        var initialSuamiId = "{{ old('suami_id') }}";
                        if ($('#select2-penduduk').val()) {
                            filterSuami(initialSuamiId);
                        }
                    });
                </script>

                <!-- Card: Informasi Kehamilan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-baby-bottle-outline text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Informasi Kehamilan</h3>
                        </div>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Usia Kehamilan (Minggu) <span class="text-red-500">*</span></label>
                            <input type="number" name="usia_kehamilan_minggu" value="{{ old('usia_kehamilan_minggu') }}" placeholder="Contoh: 12" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                            @error('usia_kehamilan_minggu') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">HPHT (Hari Pertama Haid Terakhir)</label>
                            <input type="date" name="hpht" value="{{ old('hpht') }}" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                            @error('hpht') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Taksiran Persalinan</label>
                            <input type="date" name="taksiran_persalinan" value="{{ old('taksiran_persalinan') }}" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                            @error('taksiran_persalinan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Kunjungan <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_kunjungan" value="{{ old('jumlah_kunjungan', 0) }}" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                            @error('jumlah_kunjungan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-8">
                <!-- Card: Status & Keterangan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2 bg-orange-500/10 rounded-lg mr-3">
                                <i class="mdi mdi-information text-orange-600"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">Status</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="melahirkan" {{ old('status') == 'melahirkan' ? 'selected' : '' }}>Melahirkan</option>
                                <option value="keguguran" {{ old('status') == 'keguguran' ? 'selected' : '' }}>Keguguran</option>
                                <option value="pindah" {{ old('status') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                            </select>
                            @error('status') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Faktor Risiko</label>
                            <select name="faktor_resiko" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                <option value="">Tidak ada faktor risiko</option>
                                <option value="umur < 20 th ATAU > 35 th" {{ old('faktor_resiko') == 'umur < 20 th ATAU > 35 th' ? 'selected' : '' }}>umur &lt; 20 th ATAU &gt; 35 th</option>
                                <option value="Jumlah anak > 4" {{ old('faktor_resiko') == 'Jumlah anak > 4' ? 'selected' : '' }}>Jumlah anak &gt; 4</option>
                                <option value="Jarak Hamil < 2 th" {{ old('faktor_resiko') == 'Jarak Hamil < 2 th' ? 'selected' : '' }}>Jarak Hamil &lt; 2 th</option>
                                <option value="lila kurang dr 23,5 cm" {{ old('faktor_resiko') == 'lila kurang dr 23,5 cm' ? 'selected' : '' }}>lila kurang dr 23,5 cm</option>
                                <option value="TB < 145 cm" {{ old('faktor_resiko') == 'TB < 145 cm' ? 'selected' : '' }}>TB &lt; 145 cm</option>
                            </select>
                            @error('faktor_resiko') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan</label>
                            <textarea name="keterangan" rows="4" placeholder="Catatan tambahan..." 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">{{ old('keterangan') }}</textarea>
                            @error('keterangan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="mdi mdi-check-circle text-xl"></i>
                        Simpan Data Ibu Hamil
                    </button>
                    <a href="{{ route('ibu-hamils.index') }}" class="w-full mt-4 bg-white text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-layout>
