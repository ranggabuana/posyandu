<x-layout title="Tambah Jadwal Pelayanan">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
            <a href="{{ route('jadwals.index') }}" class="hover:text-blue-600 transition-colors">Jadwal Pelayanan</a>
            <i class="mdi mdi-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Tambah Data</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Tambah Jadwal Pelayanan</h2>
        <p class="text-gray-500 text-sm mt-1">Lengkapi form berikut untuk menambahkan jadwal pelayanan Posyandu.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
        <form action="{{ route('jadwals.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Hari / Tanggal -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Hari / Tanggal <span class="text-red-500">*</span></label>
                    <input type="text" name="hari_tanggal" value="{{ old('hari_tanggal') }}" required placeholder="Contoh: Senin, Minggu ke-1"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    @error('hari_tanggal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kegiatan -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="kegiatan" value="{{ old('kegiatan') }}" required placeholder="Contoh: Bayi & Balita"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    @error('kegiatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Jam Mulai -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    @error('jam_mulai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Jam Selesai -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada batas waktu selesai.</p>
                    @error('jam_selesai')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-semibold text-gray-700">Keterangan</label>
                    <textarea name="keterangan" rows="3" placeholder="Informasi tambahan atau yang perlu dibawa..."
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('jadwals.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20 transition-all flex items-center gap-2">
                    <i class="mdi mdi-content-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</x-layout>
