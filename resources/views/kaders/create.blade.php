<x-layout title="Tambah Kader Posyandu">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Kader Baru</h2>
            <p class="text-gray-500 mt-1">Lengkapi informasi di bawah untuk menambahkan anggota kader posyandu.</p>
        </div>
        <a href="{{ route('kaders.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('kaders.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <!-- Card: Detail Kader -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-account-tie text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Detail Anggota Kader</h3>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Penduduk <span class="text-red-500">*</span></label>
                            <select name="penduduk_id" id="select2-penduduk" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                <option></option>
                                @foreach($penduduks as $p)
                                    <option value="{{ $p->id }}" {{ old('penduduk_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama }} ({{ $p->nik }})
                                    </option>
                                @endforeach
                            </select>
                            @error('penduduk_id') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @php
                                $selectedPosyanduId = auth()->user()->hasRole('posyandu') 
                                    ? auth()->user()->posyandu_id 
                                    : (request('posyandu_id') ?? old('posyandu_id'));
                                $preSelectedPosyandu = $selectedPosyanduId ? \App\Models\Posyandu::find($selectedPosyanduId) : null;
                            @endphp

                            @if($preSelectedPosyandu)
                                <input type="hidden" name="posyandu_id" value="{{ $preSelectedPosyandu->id }}">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Posyandu Tempat Tugas</label>
                                    <input type="text" value="{{ $preSelectedPosyandu->nama }}" disabled 
                                        class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-500 font-medium outline-none cursor-not-allowed">
                                </div>
                            @else
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Posyandu Tempat Tugas <span class="text-red-500">*</span></label>
                                    <select name="posyandu_id" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none pr-8 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                        <option value="">Pilih Posyandu</option>
                                        @foreach($posyandus as $posyandu)
                                            <option value="{{ $posyandu->id }}" {{ old('posyandu_id') == $posyandu->id ? 'selected' : '' }}>{{ $posyandu->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('posyandu_id') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Jabatan / Peran <span class="text-red-500">*</span></label>
                                <select name="jabatan" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none pr-8 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="Anggota" {{ old('jabatan') == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                                    <option value="Ketua" {{ old('jabatan') == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                                    <option value="Sekretaris" {{ old('jabatan') == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                                    <option value="Bendahara" {{ old('jabatan') == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                                </select>
                                @error('jabatan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Mulai Tugas</label>
                                <input type="date" name="tanggal_mulai_tugas" value="{{ old('tanggal_mulai_tugas') }}"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                                @error('tanggal_mulai_tugas') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pelatihan Kesehatan yang Pernah Diikuti</label>
                            <textarea name="pelatihan" rows="3" placeholder="Contoh: Pelatihan PMT (2024), Pelatihan Pengukuran Tinggi Badan (2025)..."
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">{{ old('pelatihan') }}</textarea>
                            @error('pelatihan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan Lainnya</label>
                            <textarea name="keterangan" rows="3" placeholder="Catatan tambahan mengenai kader..."
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">{{ old('keterangan') }}</textarea>
                            @error('keterangan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Form Status -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2 bg-orange-500/10 rounded-lg mr-3">
                                <i class="mdi mdi-information text-orange-600"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">Status Keaktifan</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status_aktif" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                <option value="1" {{ old('status_aktif', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('status_aktif', 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status_aktif') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="mdi mdi-check-circle text-xl"></i>
                        Simpan Data Kader
                    </button>
                    <a href="{{ route('kaders.index') }}" class="w-full mt-4 bg-white text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        $(document).ready(function() {
            const pendudukSelect = $('#select2-penduduk');
            
            pendudukSelect.select2({
                placeholder: 'Cari Penduduk berdasarkan Nama atau NIK...',
                allowClear: true,
                width: '100%'
            });

            const posyanduSelect = $('select[name="posyandu_id"]');
            if (posyanduSelect.length) {
                function updatePendudukOptions(posyanduId, selectedId = null) {
                    if (!posyanduId) {
                        pendudukSelect.empty().append('<option></option>').val(null).trigger('change');
                        return;
                    }
                    
                    pendudukSelect.empty().append('<option>Memuat data penduduk...</option>').val(null).trigger('change');

                    $.ajax({
                        url: '{{ route("kaders.get-penduduks") }}',
                        type: 'GET',
                        data: { posyandu_id: posyanduId },
                        success: function(data) {
                            pendudukSelect.empty().append('<option></option>');
                            data.forEach(function(item) {
                                const option = new Option(`${item.nama} (${item.nik})`, item.id, false, item.id == selectedId);
                                pendudukSelect.append(option);
                            });
                            if (!selectedId) {
                                pendudukSelect.val(null);
                            }
                            pendudukSelect.trigger('change');
                        },
                        error: function() {
                            pendudukSelect.empty().append('<option></option>').val(null).trigger('change');
                        }
                    });
                }

                posyanduSelect.on('change', function() {
                    updatePendudukOptions($(this).val());
                });

                if (posyanduSelect.val() && pendudukSelect.children('option').length <= 2) {
                    updatePendudukOptions(posyanduSelect.val(), '{{ old("penduduk_id") }}');
                }
            }
        });
    </script>
    @endpush
</x-layout>
