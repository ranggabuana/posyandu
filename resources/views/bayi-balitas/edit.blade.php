
<x-layout title="Edit Bayi & Balita">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Data Bayi & Balita</h2>
            <p class="text-gray-500 mt-1">Perbarui informasi kelahiran dan kesehatan anak di bawah ini.</p>
        </div>
        <a href="{{ route('bayi-balitas.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('bayi-balitas.update', $bayiBalita) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <!-- Card: Identitas Anak -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-baby-face text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Identitas Anak</h3>
                        </div>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Penduduk (Anak) <span class="text-red-500">*</span></label>
                            <select name="penduduk_id" id="select2-anak"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                <option></option>
                                @foreach($penduduks as $penduduk)
                                    <option value="{{ $penduduk->id }}" {{ old('penduduk_id', $bayiBalita->penduduk_id) == $penduduk->id ? 'selected' : '' }}>
                                        {{ $penduduk->nama }} ({{ $penduduk->nik }})
                                    </option>
                                @endforeach
                            </select>
                            @error('penduduk_id') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Ibu <span class="text-red-500">*</span></label>
                            <select name="nama_ibu" id="select2-ibu"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                <option></option>
                                @foreach($ibus as $ibu)
                                    <option value="{{ $ibu->nama }}" {{ old('nama_ibu', $bayiBalita->nama_ibu) == $ibu->nama ? 'selected' : '' }}>
                                        {{ $ibu->nama }} ({{ $ibu->nik }})
                                    </option>
                                @endforeach
                            </select>
                            @error('nama_ibu') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <script>
                    $(document).ready(function() {
                        $('#select2-anak').select2({
                            placeholder: 'Cari dan pilih nama Anak...',
                            allowClear: true,
                            width: '100%',
                            language: { noResults: () => "Data tidak ditemukan" }
                        });
                        $('#select2-ibu').select2({
                            placeholder: 'Cari dan pilih nama Ibu...',
                            allowClear: true,
                            width: '100%',
                            language: { noResults: () => "Data tidak ditemukan" }
                        });
                    });
                </script>

                <!-- Card: Informasi Kelahiran -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-green-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-clipboard-pulse-outline text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Informasi Kelahiran</h3>
                        </div>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $bayiBalita->tanggal_lahir) }}" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                            @error('tanggal_lahir') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Golongan Darah</label>
                            <select name="goldar" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_0.75rem_center] bg-no-repeat">
                                <option value="">Tidak Tahu</option>
                                @foreach(['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $gd)
                                    <option value="{{ $gd }}" {{ old('goldar', $bayiBalita->goldar) == $gd ? 'selected' : '' }}>{{ $gd }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Berat Lahir (Kg) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="berat_lahir" value="{{ old('berat_lahir', $bayiBalita->berat_lahir) }}" placeholder="Contoh: 3.2" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                            @error('berat_lahir') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Panjang Lahir (Cm) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.1" name="panjang_lahir" value="{{ old('panjang_lahir', $bayiBalita->panjang_lahir) }}" placeholder="Contoh: 48" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                            @error('panjang_lahir') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-8">
                <!-- Card: Administrasi -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-500/10 rounded-lg mr-3">
                                <i class="mdi mdi-office-building text-purple-600"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">Administrasi</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        @if(auth()->user()->hasRole('posyandu') && auth()->user()->posyandu)
                            <input type="hidden" name="posyandu_id" value="{{ auth()->user()->posyandu_id }}">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Posyandu</label>
                                <div class="px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-gray-800 font-semibold flex items-center gap-2">
                                    <i class="mdi mdi-office-building text-purple-600"></i>
                                    <span>{{ auth()->user()->posyandu->nama }}</span>
                                </div>
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Posyandu <span class="text-red-500">*</span></label>
                                <select name="posyandu_id" 
                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="" disabled>Pilih Posyandu</option>
                                    @foreach($posyandus as $posyandu)
                                        <option value="{{ $posyandu->id }}" {{ old('posyandu_id', $bayiBalita->posyandu_id) == $posyandu->id ? 'selected' : '' }}>{{ $posyandu->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status Akta Kelahiran</label>
                            <select name="status_akta" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                <option value="punya" {{ old('status_akta', $bayiBalita->status_akta ?? 'punya') == 'punya' ? 'selected' : '' }}>Punya Akta</option>
                                <option value="tidak punya" {{ old('status_akta', $bayiBalita->status_akta) == 'tidak punya' ? 'selected' : '' }}>Tidak Punya</option>
                            </select>
                            @error('status_akta') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="bpjs" value="1" {{ old('bpjs', $bayiBalita->bpjs) ? 'checked' : '' }} class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ms-3 text-sm font-bold text-gray-700 group-hover:text-blue-500 transition-colors">Memiliki BPJS</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan</label>
                            <textarea name="keterangan" rows="3" placeholder="Catatan tambahan..." 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">{{ old('keterangan', $bayiBalita->keterangan) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="mdi mdi-check-circle text-xl"></i>
                        Update Data Anak
                    </button>
                    <a href="{{ route('bayi-balitas.index') }}" class="w-full mt-4 bg-white text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-layout>
