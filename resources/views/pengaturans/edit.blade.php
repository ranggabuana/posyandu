
<x-layout title="Edit Pengaturan">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Edit Pengaturan</h2>
            <p class="text-gray-500 mt-1">Perbarui informasi pengaturan di bawah ini.</p>
        </div>
        <a href="{{ route('pengaturans.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <i class="mdi mdi-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="{{ route('pengaturans.update', $pengaturan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                <!-- Card: Informasi Pengaturan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                                <i class="mdi mdi-cog text-blue-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Informasi Pengaturan</h3>
                        </div>
                    </div>
                    <div class="p-8 space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Key</label>
                            <input type="text" value="{{ $pengaturan->key }}" disabled 
                                class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Label</label>
                            <input type="text" name="label" value="{{ old('label', $pengaturan->label) }}" placeholder="Masukkan label" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                            @error('label') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>

                        @if ($pengaturan->key === 'logo_desa')
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Logo Desa</label>
                                <!-- Drag and Drop File Upload Container -->
                                <div id="logo-dropzone" class="border-2 border-dashed border-gray-200 hover:border-blue-500 rounded-2xl p-6 transition-all duration-200 bg-gray-50/50 hover:bg-blue-50/10 cursor-pointer text-center relative">
                                    <input type="file" name="value" id="logo-file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                    
                                    <div id="logo-preview-container" class="{{ $pengaturan->value ? '' : 'hidden' }} mb-4 flex justify-center">
                                        <div class="relative inline-block">
                                            <img id="logo-preview" 
                                                src="{{ $pengaturan->value ? (Str::startsWith($pengaturan->value, ['http://', 'https://']) ? $pengaturan->value : asset('storage/' . $pengaturan->value)) : '#' }}" 
                                                alt="Preview" 
                                                class="h-32 object-contain rounded-xl border bg-white p-2">
                                            <button type="button" id="btn-remove-logo" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white p-1 rounded-full shadow-lg transition-all focus:outline-none z-20">
                                                <i class="mdi mdi-close-circle text-lg"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div id="logo-placeholder" class="{{ $pengaturan->value ? 'hidden' : '' }} space-y-2 py-4">
                                        <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto">
                                            <i class="mdi mdi-cloud-upload text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-600">Klik atau seret file gambar logo desa ke sini</p>
                                        <p class="text-xs text-gray-400">PNG, JPG, JPEG (Maks. 2MB)</p>
                                    </div>
                                </div>
                                <input type="hidden" name="remove_logo" id="remove-logo-input" value="0">
                                @error('value') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>
                        @elseif ($pengaturan->key === 'jam_operasional' || $pengaturan->key === 'alamat_desa' || $pengaturan->key === 'moto')
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Value</label>
                                <textarea name="value" rows="4" placeholder="Masukkan value" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">{{ old('value', $pengaturan->value) }}</textarea>
                                @error('value') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Value</label>
                                <input type="text" name="value" value="{{ old('value', $pengaturan->value) }}" placeholder="Masukkan value" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                @error('value') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                            </div>
                        @endif
                    </div>
                </div>
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
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">{{ old('keterangan', $pengaturan->keterangan) }}</textarea>
                            @error('keterangan') <p class="text-red-500 text-xs mt-2 flex items-center"><i class="mdi mdi-alert-circle mr-1"></i> {{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="mdi mdi-check-circle text-xl"></i>
                        Update Pengaturan
                    </button>
                    <a href="{{ route('pengaturans.index') }}" class="w-full mt-4 bg-white text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-layout>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('logo-file');
        const previewContainer = document.getElementById('logo-preview-container');
        const previewImage = document.getElementById('logo-preview');
        const placeholder = document.getElementById('logo-placeholder');
        const btnRemove = document.getElementById('btn-remove-logo');
        const removeInput = document.getElementById('remove-logo-input');

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                        removeInput.value = "0";
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        if (btnRemove) {
            btnRemove.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                fileInput.value = "";
                previewImage.src = "#";
                previewContainer.classList.add('hidden');
                placeholder.classList.remove('hidden');
                removeInput.value = "1";
            });
        }
    });
</script>
@endpush
