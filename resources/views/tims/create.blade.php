<x-layout title="Tambah Anggota Tim">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
            <a href="{{ route('tims.index') }}" class="hover:text-blue-600 transition-colors">Manajemen Tim</a>
            <i class="mdi mdi-chevron-right text-xs"></i>
            <span class="text-gray-900 font-medium">Tambah Data</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Tambah Anggota Tim</h2>
        <p class="text-gray-500 text-sm mt-1">Lengkapi form berikut untuk menambahkan profil anggota tim posyandu baru.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
        <form action="{{ route('tims.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div class="space-y-2 md:col-span-1">
                    <label class="text-sm font-semibold text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: dr. Sari Dewi"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jabatan -->
                <div class="space-y-2 md:col-span-1">
                    <label class="text-sm font-semibold text-gray-700">Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="jabatan" value="{{ old('jabatan') }}" required placeholder="Contoh: Dokter Koordinator"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    @error('jabatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-semibold text-gray-700">Deskripsi / Pengalaman</label>
                    <textarea name="deskripsi" rows="3" placeholder="Informasi mengenai latar belakang atau pengalaman anggota tim..."
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Profil -->
                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-semibold text-gray-700 block">Foto Profil (Opsional)</label>
                    <div class="mt-2 flex items-center gap-6">
                        <div id="image-preview-container" class="hidden relative w-32 h-32 rounded-2xl overflow-hidden border border-gray-200 shadow-sm group bg-gray-50 flex items-center justify-center">
                            <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover" />
                            <button type="button" id="remove-image" class="absolute inset-0 bg-black/50 text-white flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="mdi mdi-delete text-2xl"></i>
                                <span class="text-xs font-medium mt-1">Hapus</span>
                            </button>
                        </div>
                        <div id="image-placeholder" class="w-32 h-32 rounded-2xl border-2 border-dashed border-gray-300 bg-gray-50 text-gray-400 flex flex-col items-center justify-center hover:bg-gray-100 transition-colors">
                            <i class="mdi mdi-image-outline text-3xl mb-1"></i>
                            <span class="text-xs">Preview Foto</span>
                        </div>
                        <div class="flex-1 max-w-sm">
                            <input type="file" name="foto" id="foto-input" accept="image/*"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer text-gray-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                            <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maksimal: 2MB.</p>
                            <p class="text-xs text-gray-400">Pilih gambar rasio kotak (1:1) untuk hasil terbaik. Jika tidak ada gambar, sistem akan memberikan ikon inisial pada landing page.</p>
                            @error('foto')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('tims.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20 transition-all flex items-center gap-2">
                    <i class="mdi mdi-content-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</x-layout>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('foto-input');
        const previewContainer = document.getElementById('image-preview-container');
        const placeholder = document.getElementById('image-placeholder');
        const previewImg = document.getElementById('image-preview');
        const removeBtn = document.getElementById('remove-image');

        fileInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                
                reader.readAsDataURL(e.target.files[0]);
            } else {
                resetPreview();
            }
        });

        removeBtn.addEventListener('click', function() {
            fileInput.value = '';
            resetPreview();
        });

        function resetPreview() {
            previewImg.src = '#';
            previewContainer.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    });
</script>
@endpush
