<x-layout title="Tambah Posyandu">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Posyandu Baru</h2>
            <p class="text-gray-500 mt-1">Lengkapi informasi posyandu beserta pembuatan akun login pengurus.</p>
        </div>
        <a href="{{ route('posyandus.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('posyandus.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <!-- Card: Detail Posyandu -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-home-heart text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Detail Posyandu</h3>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Posyandu <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Posyandu Melati..." required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                                @error('nama') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Ketua Posyandu</label>
                                <input type="text" name="ketua" value="{{ old('ketua') }}" placeholder="Nama Ketua"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                                @error('ketua') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">No. Telepon / WhatsApp</label>
                                <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="No. Telepon"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                                @error('telepon') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Dusun <span class="text-red-500">*</span></label>
                                <select name="dusun" id="select-dusun" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="">Pilih Dusun</option>
                                    @foreach($dusuns as $dusun)
                                        <option value="{{ $dusun }}" {{ old('dusun') == $dusun ? 'selected' : '' }}>{{ $dusun }}</option>
                                    @endforeach
                                </select>
                                @error('dusun') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- RW Diampu Checkboxes Container -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-3">RW Diampu <span class="text-red-500">*</span></label>
                            <div id="rw-checkbox-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200 min-h-[60px] items-center">
                                <p class="text-xs text-gray-500 col-span-full">Pilih Dusun terlebih dahulu untuk memuat daftar RW...</p>
                            </div>
                            @error('rw_diampu') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Posyandu</label>
                            <textarea name="alamat" rows="3" placeholder="Alamat lengkap lokasi..."
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">{{ old('alamat') }}</textarea>
                            @error('alamat') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jadwal Rutin</label>
                                <input type="text" name="jadwal_rutin" value="{{ old('jadwal_rutin') }}" placeholder="Contoh: Setiap Tanggal 15"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                                @error('jadwal_rutin') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Titik Koordinat (Lokasi)</label>
                                <input type="text" name="lokasi_koordinat" value="{{ old('lokasi_koordinat') }}" placeholder="-7.432, 109.213"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                                @error('lokasi_koordinat') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status Bangunan</label>
                            <input type="text" name="bangunan" value="{{ old('bangunan') }}" placeholder="Contoh: Gedung Sendiri, Numpang Rumah Ketua, dll."
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                            @error('bangunan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Card: Sarana & Prasarana -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-orange-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-toolbox text-orange-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Sarana & Prasarana</h3>
                        </div>
                    </div>
                    <div class="p-8 space-y-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Peralatan yang Dimiliki</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 cursor-pointer select-none">
                                <input type="checkbox" name="punya_timbangan_dacin" value="1" {{ old('punya_timbangan_dacin') ? 'checked' : '' }} class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Timbangan Dacin</span>
                            </label>

                            <label class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 cursor-pointer select-none">
                                <input type="checkbox" name="punya_timbangan_digital" value="1" {{ old('punya_timbangan_digital') ? 'checked' : '' }} class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Timbangan Bayi Digital</span>
                            </label>

                            <label class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 cursor-pointer select-none">
                                <input type="checkbox" name="punya_alat_ukur_tinggi" value="1" {{ old('punya_alat_ukur_tinggi') ? 'checked' : '' }} class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Alat Ukur Tinggi (Microtoise/Stadiometer)</span>
                            </label>

                            <label class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 cursor-pointer select-none">
                                <input type="checkbox" name="punya_pita_liLa" value="1" {{ old('punya_pita_liLa') ? 'checked' : '' }} class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Pita LiLa (Lingkar Lengan & Kepala)</span>
                            </label>

                            <label class="flex items-center gap-3 p-3 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 cursor-pointer select-none">
                                <input type="checkbox" name="punya_buku_kia" value="1" {{ old('punya_buku_kia') ? 'checked' : '' }} class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-700">Persediaan Buku KIA</span>
                            </label>
                        </div>

                        <div class="pt-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan Lainnya</label>
                            <textarea name="keterangan_lain" rows="3" placeholder="Informasi prasarana tambahan..."
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">{{ old('keterangan_lain') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info & Akun -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-500/10 rounded-lg mr-3">
                                <i class="mdi mdi-key text-purple-600"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">Akun Pengguna</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Username Login <span class="text-red-500">*</span></label>
                            <input type="text" name="username" value="{{ old('username') }}" placeholder="Username pengurus" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                            @error('username') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" placeholder="Minimal 8 karakter" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                            @error('password') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-gray-100">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="mdi mdi-check-circle text-xl"></i>
                        Simpan Posyandu
                    </button>
                    <a href="{{ route('posyandus.index') }}" class="w-full mt-4 bg-white text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            function loadRws(dusunName, selectedRws = []) {
                if (!dusunName) {
                    $('#rw-checkbox-container').html('<p class="text-xs text-gray-500 col-span-full">Pilih Dusun terlebih dahulu untuk memuat daftar RW...</p>');
                    return;
                }

                $('#rw-checkbox-container').html('<p class="text-xs text-gray-500 col-span-full flex items-center gap-2"><i class="mdi mdi-loading mdi-spin text-lg"></i> Memuat RW...</p>');

                $.ajax({
                    url: '{{ route("posyandus.rws") }}',
                    type: 'GET',
                    data: { dusun: dusunName },
                    success: function(data) {
                        if (data && data.length > 0) {
                            let html = '';
                            data.forEach(function(rw) {
                                const isChecked = selectedRws.includes(rw) ? 'checked' : '';
                                html += `
                                    <label class="flex items-center gap-2 p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer select-none">
                                        <input type="checkbox" name="rw_diampu[]" value="${rw}" ${isChecked} class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-sm font-medium text-gray-750">RW ${rw}</span>
                                    </label>
                                `;
                            });
                            $('#rw-checkbox-container').html(html);
                        } else {
                            $('#rw-checkbox-container').html('<p class="text-xs text-gray-500 col-span-full text-red-500">Tidak ada RW terdaftar di dusun ini.</p>');
                        }
                    },
                    error: function() {
                        $('#rw-checkbox-container').html('<p class="text-xs text-gray-500 col-span-full text-red-500">Gagal mengambil data RW.</p>');
                    }
                });
            }

            $('#select-dusun').change(function() {
                loadRws($(this).val());
            });

            // Initial load if validation failed and dusun was old-selected
            const oldDusun = '{{ old("dusun") }}';
            const oldRws = @json(old('rw_diampu', []));
            if (oldDusun) {
                loadRws(oldDusun, oldRws);
            }
        });
    </script>
</x-layout>
