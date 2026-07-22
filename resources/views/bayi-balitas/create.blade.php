
<x-layout title="Tambah Bayi & Balita">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Data Bayi & Balita</h2>
            <p class="text-gray-500 mt-1">Lengkapi informasi kelahiran dan kesehatan anak di bawah ini.</p>
        </div>
        <a href="{{ route('bayi-balitas.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('bayi-balitas.store') }}" method="POST">
        @csrf

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
                            <label class="inline-flex items-center cursor-pointer mb-2">
                                <input type="checkbox" name="input_manual" id="input_manual" value="1" {{ old('input_manual') ? 'checked' : '' }} class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-500/10 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ms-3 text-sm font-bold text-gray-700">Isi data anak secara manual (belum terdaftar di Penduduk)</span>
                            </label>
                        </div>

                        <!-- Dropdown Pilih Penduduk (Anak) -->
                        <div class="md:col-span-2" id="div-pilih-anak">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Anak <span class="text-red-500">*</span></label>
                            <select name="penduduk_id" id="select2-anak"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                <option></option>
                                @foreach($penduduks as $penduduk)
                                    @php
                                        $ageMonths = $penduduk->tanggallahir ? \Carbon\Carbon::parse($penduduk->tanggallahir)->diffInMonths(\Carbon\Carbon::now()) : '-';
                                        $ageYears = $penduduk->tanggallahir ? \Carbon\Carbon::parse($penduduk->tanggallahir)->age : '-';
                                    @endphp
                                    <option value="{{ $penduduk->id }}" data-tanggallahir="{{ $penduduk->tanggallahir }}" {{ old('penduduk_id') == $penduduk->id ? 'selected' : '' }}>
                                        {{ $penduduk->nama }} (NIK: {{ $penduduk->nik }} - {{ $ageMonths }} Bln / {{ $ageYears }} Thn)
                                    </option>
                                @endforeach
                            </select>
                            @error('penduduk_id') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <!-- Fields Input Manual Anak -->
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6" id="div-manual-anak">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Anak <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" id="input-nama" value="{{ old('nama') }}" placeholder="Nama Lengkap Anak"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                @error('nama') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">NIK Anak (Opsional)</label>
                                <input type="text" name="nik" id="input-nik" value="{{ old('nik') }}" placeholder="16 Digit NIK"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                @error('nik') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
                                <input type="text" name="tempat_lahir" id="input-tempat-lahir" value="{{ old('tempat_lahir') }}" placeholder="Kabupaten/Kota Lahir"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                @error('tempat_lahir') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_lahir_manual" id="input-tanggal-lahir" value="{{ old('tanggal_lahir_manual') }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                @error('tanggal_lahir_manual') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select name="kelamin" id="input-kelamin"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="laki-laki" {{ old('kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="perempuan" {{ old('kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('kelamin') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Ibu <span class="text-red-500">*</span></label>
                            <select name="nama_ibu" id="select2-ibu"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                <option></option>
                                @foreach($ibus as $ibu)
                                    <option value="{{ $ibu->nama }}" {{ old('nama_ibu') == $ibu->nama ? 'selected' : '' }}>
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

                        function toggleManualInput() {
                            if ($('#input_manual').is(':checked')) {
                                $('#div-pilih-anak').addClass('hidden');
                                $('#div-manual-anak').removeClass('hidden');
                                $('#select2-anak').prop('disabled', true);
                                $('#input-nama').prop('disabled', false);
                                $('#input-nik').prop('disabled', false);
                                $('#input-tempat-lahir').prop('disabled', false);
                                $('#input-tanggal-lahir').prop('disabled', false);
                                $('#input-kelamin').prop('disabled', false);

                                $('#div-tanggal-lahir-non-manual').addClass('hidden');
                                $('#input-tanggal-lahir-non-manual').prop('disabled', true);
                            } else {
                                $('#div-pilih-anak').removeClass('hidden');
                                $('#div-manual-anak').addClass('hidden');
                                $('#select2-anak').prop('disabled', false);
                                $('#input-nama').prop('disabled', true);
                                $('#input-nik').prop('disabled', true);
                                $('#input-tempat-lahir').prop('disabled', true);
                                $('#input-tanggal-lahir').prop('disabled', true);
                                $('#input-kelamin').prop('disabled', true);

                                $('#div-tanggal-lahir-non-manual').removeClass('hidden');
                                $('#input-tanggal-lahir-non-manual').prop('disabled', false);
                            }
                        }

                        $('#input_manual').on('change', function() {
                            toggleManualInput();
                        });

                        $('#select2-anak').on('change', function() {
                            const selectedOpt = $(this).find('option:selected');
                            const dob = selectedOpt.data('tanggallahir');
                            if (dob) {
                                $('#input-tanggal-lahir-non-manual').val(dob);
                            }
                        });

                        toggleManualInput();
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
                        <div id="div-tanggal-lahir-non-manual">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" id="input-tanggal-lahir-non-manual" value="{{ old('tanggal_lahir') }}" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                            @error('tanggal_lahir') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Golongan Darah</label>
                            <select name="goldar" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_0.75rem_center] bg-no-repeat">
                                <option value="">Tidak Tahu</option>
                                @foreach(['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $gd)
                                    <option value="{{ $gd }}" {{ old('goldar') == $gd ? 'selected' : '' }}>{{ $gd }}</option>
                                @endforeach
                            </select>
                            @error('goldar') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Berat Lahir (Kg) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="berat_lahir" value="{{ old('berat_lahir') }}" placeholder="Contoh: 3.2" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                            @error('berat_lahir') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Panjang Lahir (Cm) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.1" name="panjang_lahir" value="{{ old('panjang_lahir') }}" placeholder="Contoh: 48" 
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
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Posyandu <span class="text-red-500">*</span></label>
                            <select name="posyandu_id" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                <option value="" disabled selected>Pilih Posyandu</option>
                                @foreach($posyandus as $posyandu)
                                    <option value="{{ $posyandu->id }}" {{ old('posyandu_id') == $posyandu->id ? 'selected' : '' }}>{{ $posyandu->nama }}</option>
                                @endforeach
                            </select>
                            @error('posyandu_id') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="bpjs" value="1" {{ old('bpjs') ? 'checked' : '' }} class="sr-only peer">
                                <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ms-3 text-sm font-bold text-gray-700 group-hover:text-blue-500 transition-colors">Memiliki BPJS</span>
                            </label>
                            @error('bpjs') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan</label>
                            <textarea name="keterangan" rows="3" placeholder="Catatan tambahan..." 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">{{ old('keterangan') }}</textarea>
                            @error('keterangan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="mdi mdi-check-circle text-xl"></i>
                        Simpan Data Anak
                    </button>
                    <a href="{{ route('bayi-balitas.index') }}" class="w-full mt-4 bg-white text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-layout>
