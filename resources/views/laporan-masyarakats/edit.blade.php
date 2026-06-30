
<x-layout title="Edit Laporan Masyarakat">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tindak Lanjut Laporan</h2>
            <p class="text-gray-500 mt-1">Berikan balasan atau ubah status laporan.</p>
        </div>
        <a href="{{ route('laporan-masyarakats.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('laporan-masyarakats.update', $laporanMasyarakat) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <!-- Card: Isi Laporan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-message-alert text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Isi Laporan</h3>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-500 mb-1">Hari / Tanggal Kejadian</label>
                                <p class="text-gray-900 font-semibold">{{ $laporanMasyarakat->hari_tanggal ? \Carbon\Carbon::parse($laporanMasyarakat->hari_tanggal)->translatedFormat('l, d F Y') : '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-500 mb-1">Nama Lengkap</label>
                                <p class="text-gray-900 font-semibold">{{ $laporanMasyarakat->nama_pelapor }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-500 mb-1">No. KTP</label>
                                <p class="text-gray-900 font-semibold">{{ $laporanMasyarakat->nik_pelapor ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-500 mb-1">No. KK</label>
                                <p class="text-gray-900 font-semibold">{{ $laporanMasyarakat->no_kk ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-500 mb-1">No. HP</label>
                                <p class="text-gray-900 font-semibold">{{ $laporanMasyarakat->no_telepon ?? '-' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-500 mb-1">Alamat</label>
                                <p class="text-gray-900 font-semibold">{{ $laporanMasyarakat->alamat ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-500 mb-1">Posyandu Tujuan</label>
                                <p class="text-gray-900 font-semibold">{{ $laporanMasyarakat->posyandu->nama ?? '-' }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-500 mb-1">Jenis Keperluan</label>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase">{{ $laporanMasyarakat->kategori }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-500 mb-1">Keterangan</label>
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 text-gray-800">
                                {{ $laporanMasyarakat->isi_laporan }}
                            </div>
                        </div>

                        @if($laporanMasyarakat->foto_bukti)
                        <div>
                            <label class="block text-sm font-bold text-gray-500 mb-2">Foto Bukti</label>
                            <img src="{{ asset('storage/' . $laporanMasyarakat->foto_bukti) }}" class="w-full max-h-80 object-cover rounded-2xl border border-gray-100 shadow-sm">
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Card: Balasan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-green-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-reply text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Berikan Tanggapan</h3>
                        </div>
                    </div>
                    <div class="p-8">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Balasan / Solusi <span class="text-red-500">*</span></label>
                        <textarea name="balasan" rows="6" placeholder="Tuliskan jawaban atau tindak lanjut untuk pelapor..." 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">{{ old('balasan', $laporanMasyarakat->balasan) }}</textarea>
                        @error('balasan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-8">
                <!-- Card: Status -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2 bg-orange-500/10 rounded-lg mr-3">
                                <i class="mdi mdi-clock-outline text-orange-600"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">Ubah Status</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Status Laporan</label>
                        <select name="status" 
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                            <option value="baru" {{ old('status', $laporanMasyarakat->status) == 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="diproses" {{ old('status', $laporanMasyarakat->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ old('status', $laporanMasyarakat->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ old('status', $laporanMasyarakat->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        @error('status') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="mdi mdi-check-circle text-xl"></i>
                        Simpan Tindak Lanjut
                    </button>
                    <a href="{{ route('laporan-masyarakats.index') }}" class="w-full mt-4 bg-white text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-layout>
