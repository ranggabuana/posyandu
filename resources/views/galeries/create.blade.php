
<x-layout title="Tambah Galeri">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Data Galeri</h2>
            <p class="text-gray-500 mt-1">Lengkapi informasi galeri di bawah ini dengan benar.</p>
        </div>
        <a href="{{ route('galeries.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('galeries.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <!-- Card: Informasi Galeri -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-purple-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-image-multiple text-purple-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Informasi Galeri</h3>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Posyandu</label>
                            <select name="posyandu_id" id="select2-posyandu"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                <option></option>
                                @foreach($posyandus as $posyandu)
                                    <option value="{{ $posyandu->id }}" {{ old('posyandu_id') == $posyandu->id ? 'selected' : '' }}>
                                        {{ $posyandu->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('posyandu_id') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Judul <span class="text-red-500">*</span></label>
                            <input type="text" name="judul" value="{{ old('judul') }}" placeholder="Masukkan judul galeri" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                            @error('judul') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                         <div>
                             <label class="block text-sm font-bold text-gray-700 mb-2">Foto Galeri <span class="text-red-500">*</span></label>
                             
                             <!-- Preview Container (Hidden by default) -->
                             <div id="image-preview-container" class="hidden mb-4">
                                 <span class="block text-xs font-semibold text-gray-400 mb-1.5">Preview Gambar:</span>
                                 <div class="relative inline-block rounded-2xl overflow-hidden border border-gray-200 bg-gray-50 p-2">
                                     <img id="image-preview" src="#" alt="Preview" class="h-32 object-cover rounded-xl" />
                                     <button type="button" id="remove-preview" class="absolute top-4 right-4 p-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors shadow-lg flex items-center justify-center">
                                         <i class="mdi mdi-delete text-sm"></i>
                                     </button>
                                 </div>
                             </div>

                             <div class="relative flex items-center justify-center w-full">
                                 <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100/50 transition-all duration-200">
                                     <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                         <i class="mdi mdi-image-plus text-gray-400 text-3xl mb-2"></i>
                                         <p class="mb-2 text-sm text-gray-500 font-semibold"><span class="text-blue-600">Klik untuk upload</span> atau seret gambar</p>
                                         <p class="text-xs text-gray-400">PNG, JPG, JPEG, atau GIF (Maks. 2MB)</p>
                                     </div>
                                     <input type="file" name="foto" id="foto-input" class="hidden" accept="image/*" />
                                 </label>
                             </div>
                             @error('foto') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                         </div>
                    </div>
                </div>

                <script>
                    $(document).ready(function() {
                        $('#select2-posyandu').select2({
                            placeholder: 'Pilih Posyandu...',
                            allowClear: true,
                            width: '100%',
                            language: {
                                noResults: function() {
                                    return "Data tidak ditemukan";
                                }
                            }
                        });
                    });
                </script>
            </div>

            <div class="lg:col-span-4 space-y-8">
                <!-- Card: Keterangan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2 bg-orange-500/10 rounded-lg mr-3">
                                <i class="mdi mdi-information text-orange-600"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">Keterangan</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
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
                        Simpan Data Galeri
                    </button>
                    <a href="{{ route('galeries.index') }}" class="w-full mt-4 bg-white text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('foto-input');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImg = document.getElementById('image-preview');
            const removeBtn = document.getElementById('remove-preview');

            if (input) {
                input.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (event) {
                            previewImg.src = event.target.result;
                            previewContainer.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewContainer.classList.add('hidden');
                        previewImg.src = '#';
                    }
                });
            }

            if (removeBtn) {
                removeBtn.addEventListener('click', function () {
                    if (input) {
                        input.value = ''; // clear file selection
                    }
                    previewContainer.classList.add('hidden');
                    previewImg.src = '#';
                });
            }
        });
    </script>
    @endpush
</x-layout>
