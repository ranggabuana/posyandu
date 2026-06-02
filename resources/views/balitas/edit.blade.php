<x-layout title="Edit Data Balita">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Data Balita</h2>
            <p class="text-gray-500 mt-1">Ubah data anak dan status administrasi balita di bawah ini.</p>
        </div>
        <a href="{{ route('balitas.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('balitas.update', $balita) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <!-- Card: Identitas Balita -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-human-child text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Identitas Balita</h3>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <!-- Dropdown Pilih Bayi (Anak) -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Bayi (Anak) <span class="text-red-500">*</span></label>
                            <select name="bayi_balita_id" id="select2-bayi"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                <option></option>
                                @foreach($bayis as $bayi)
                                    <option value="{{ $bayi->id }}" {{ old('bayi_balita_id', $balita->bayi_balita_id) == $bayi->id ? 'selected' : '' }}>
                                        {{ $bayi->penduduk->nama }} (NIK: {{ $bayi->penduduk->nik }})
                                    </option>
                                @endforeach
                            </select>
                            @error('bayi_balita_id') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <!-- Dropdown Status Akta Kelahiran -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status Akta Kelahiran <span class="text-red-500">*</span></label>
                            <select name="status_akta" id="select-status-akta"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_0.75rem_center] bg-no-repeat">
                                <option value="punya" {{ old('status_akta', $balita->status_akta) == 'punya' ? 'selected' : '' }}>Punya</option>
                                <option value="tidak punya" {{ old('status_akta', $balita->status_akta) == 'tidak punya' ? 'selected' : '' }}>Tidak Punya</option>
                            </select>
                            @error('status_akta') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4">
                <!-- Card: Aksi -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 space-y-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="mdi mdi-check-circle text-xl"></i>
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('balitas.index') }}" class="w-full bg-white text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            $('#select2-bayi').select2({
                placeholder: 'Cari berdasarkan nama atau NIK anak...',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</x-layout>
