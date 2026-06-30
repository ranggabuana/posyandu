
<x-layout title="Edit Buku Tamu">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Buku Tamu</h2>
            <p class="text-gray-500 mt-1">Perbarui informasi kunjungan di bawah ini.</p>
        </div>
        <a href="{{ route('buku-tamus.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('buku-tamus.update', $bukuTamu) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Single Card layout -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden max-w-4xl">
            <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                <div class="flex items-center">
                    <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                        <i class="mdi mdi-account-card-details text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Formulir Edit Buku Tamu</h3>
                </div>
            </div>
            <div class="p-8 space-y-6">
                <!-- Grid of fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan', $bukuTamu->tanggal_kunjungan) }}" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                        @error('tanggal_kunjungan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $bukuTamu->nama) }}" placeholder="Masukkan nama lengkap" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                        @error('nama') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan <span class="text-red-500">*</span></label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $bukuTamu->jabatan) }}" placeholder="Masukkan jabatan" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                        @error('jabatan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat <span class="text-red-500">*</span></label>
                        <input type="text" name="alamat" value="{{ old('alamat', $bukuTamu->alamat) }}" placeholder="Masukkan alamat" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                        @error('alamat') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tujuan <span class="text-red-500">*</span></label>
                        <input type="text" name="keperluan" value="{{ old('keperluan', $bukuTamu->keperluan) }}" placeholder="Masukkan tujuan kunjungan" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                        @error('keperluan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>

                    @if(!empty($posyandus) && count($posyandus) > 0)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Posyandu <span class="text-red-500">*</span></label>
                        <select name="posyandu_id" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                            <option value="">Pilih Posyandu</option>
                            @foreach($posyandus as $p)
                                <option value="{{ $p->id }}" {{ old('posyandu_id', $bukuTamu->posyandu_id) == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                        @error('posyandu_id') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                    </div>
                    @endif
                </div>

                <!-- Kesan / Pesan (inside same card) -->
                <div class="border-t border-gray-100 pt-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kesan / Pesan</label>
                    <textarea name="keterangan" rows="4" placeholder="Tulis kesan atau pesan Anda..." 
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">{{ old('keterangan', $bukuTamu->keterangan) }}</textarea>
                    @error('keterangan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                </div>

                <!-- Action buttons embedded at bottom of card -->
                <div class="border-t border-gray-100 pt-6 flex flex-col sm:flex-row sm:justify-end gap-4">
                    <a href="{{ route('buku-tamus.index') }}" 
                        class="px-6 py-3 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        Batal
                    </a>
                    <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="mdi mdi-check-circle text-lg"></i>
                        Update Buku Tamu
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-layout>
