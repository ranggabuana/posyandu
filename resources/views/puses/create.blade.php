<x-layout title="Tambah PUS">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Data PUS</h2>
            <p class="text-sm text-gray-500">Pilih pasangan suami dan istri yang sesuai kriteria usia subur.</p>
        </div>
        <a href="{{ route('puses.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
            <i class="mdi mdi-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('puses.store') }}" method="POST" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Suami</label>
                <select name="suami_id" id="select2-suami" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                    <option value="">-- Tidak Ada / Kosong --</option>
                    @foreach($pria as $p)
                        <option value="{{ $p->id }}" {{ old('suami_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }} (NIK: {{ $p->nik }})</option>
                    @endforeach
                </select>
                @error('suami_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Istri <span class="text-red-500">*</span></label>
                <select name="istri_id" id="select2-istri" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                    <option value="">-- Pilih Istri --</option>
                    @foreach($wanita as $w)
                        <option value="{{ $w->id }}" {{ old('istri_id') == $w->id ? 'selected' : '' }}>{{ $w->nama }} (NIK: {{ $w->nik }})</option>
                    @endforeach
                </select>
                @error('istri_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            @if(auth()->user()->hasRole('posyandu') && auth()->user()->posyandu_id)
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Posyandu</label>
                    <input type="hidden" name="posyandu_id" value="{{ auth()->user()->posyandu_id }}">
                    <div class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-gray-700 font-semibold text-sm flex items-center gap-2">
                        <i class="mdi mdi-office-building text-blue-600"></i>
                        {{ auth()->user()->posyandu->nama ?? 'Posyandu Saya' }}
                    </div>
                </div>
            @else
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Posyandu</label>
                    <select name="posyandu_id" id="select2-posyandu" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                        <option value="">-- Pilih Posyandu --</option>
                        @foreach($posyandus as $p)
                            <option value="{{ $p->id }}" {{ old('posyandu_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    @error('posyandu_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            @endif

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none" placeholder="Opsional...">{{ old('keterangan') }}</textarea>
                @error('keterangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
                <i class="mdi mdi-content-save"></i> Simpan Data
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery !== 'undefined') {
                $('#select2-suami').select2({
                    placeholder: '-- Tidak Ada / Kosong --',
                    allowClear: true,
                    width: '100%'
                });
                $('#select2-istri').select2({
                    placeholder: '-- Pilih Istri --',
                    allowClear: true,
                    width: '100%'
                });
                if ($('#select2-posyandu').length) {
                    $('#select2-posyandu').select2({
                        placeholder: '-- Pilih Posyandu --',
                        allowClear: true,
                        width: '100%'
                    });
                }

                $('#select2-suami').on('change', function() {
                    var suamiId = $(this).val();
                    var istriSelect = $('#select2-istri');
                    var currentIstriId = istriSelect.val();

                    istriSelect.empty();
                    istriSelect.append(new Option('Memuat data...', '', false, false));
                    istriSelect.prop('disabled', true);
                    istriSelect.trigger('change.select2');

                    $.ajax({
                        url: '{{ route('puses.get-istri') }}',
                        data: { suami_id: suamiId },
                        dataType: 'json',
                        success: function(data) {
                            istriSelect.empty();
                            istriSelect.prop('disabled', false);
                            istriSelect.append(new Option('-- Pilih Istri --', '', false, false));
                            
                            $.each(data, function(index, wanita) {
                                var selected = (wanita.id == currentIstriId);
                                var option = new Option(wanita.nama + ' (NIK: ' + wanita.nik + ')', wanita.id, selected, selected);
                                istriSelect.append(option);
                            });
                            
                            istriSelect.trigger('change.select2');
                        },
                        error: function() {
                            istriSelect.empty();
                            istriSelect.prop('disabled', false);
                            istriSelect.append(new Option('-- Gagal memuat data --', '', false, false));
                            istriSelect.trigger('change.select2');
                        }
                    });
                });

                if ($('#select2-suami').val()) {
                    $('#select2-suami').trigger('change');
                }
            }
        });
    </script>
</x-layout>
