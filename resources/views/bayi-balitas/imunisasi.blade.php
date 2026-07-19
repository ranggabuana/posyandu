<x-layout title="Imunisasi Bayi & Balita">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Imunisasi Bayi & Balita</h2>
            <p class="text-gray-500 mt-1">Catat dan lihat riwayat pemberian imunisasi untuk <strong>{{ $bayiBalita->penduduk->nama }}</strong> (NIK: {{ $bayiBalita->penduduk->nik }}).</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('bayi-balitas.pemeriksaan', $bayiBalita) }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 transition-all">
                <i class="mdi mdi-scale-bathroom mr-2 text-lg"></i> Ke Penimbangan
            </a>
            <a href="{{ $bayiBalita->umur_bulan <= 12 ? route('bayi-balitas.index') : route('balitas.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
                <i class="mdi mdi-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Alert Success/Error -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-2">
            <i class="mdi mdi-check-circle text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Row 1: Form Imunisasi Baru & Profil Anak -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Left Side: Form Imunisasi -->
        <div class="lg:col-span-8">
            <!-- Card 1: Form Catat Imunisasi Baru -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-green-500/10 rounded-lg mr-4">
                            <i class="mdi mdi-needle text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Catat Imunisasi Baru</h3>
                    </div>
                </div>
                <div class="p-8">
                    <form action="{{ route('bayi-balitas.store-imunisasi', $bayiBalita) }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Jenis Vaksin <span class="text-red-500">*</span></label>
                                <select name="imunisasi_nama_vaksin" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.75rem_center] bg-no-repeat">
                                    <option value="" disabled selected>Pilih Vaksin...</option>
                                    @foreach($rekomendasiImunisasi as $vaksin)
                                        <option value="{{ $vaksin }}">{{ $vaksin }}</option>
                                    @endforeach
                                </select>
                                @error('imunisasi_nama_vaksin') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pemberian <span class="text-red-500">*</span></label>
                                <input type="date" name="imunisasi_tanggal_pemberian" value="{{ old('imunisasi_tanggal_pemberian', date('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none">
                                @error('imunisasi_tanggal_pemberian') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan / Nomor Batch</label>
                                <input type="text" name="imunisasi_keterangan" placeholder="Contoh: Batch No. 9812A, reaksi demam ringan"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 placeholder-gray-400 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none">
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-green-500/15 transition-all flex items-center gap-2">
                                <i class="mdi mdi-needle"></i> Catat Imunisasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side: Profil & Info Rekomendasi -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Profil Ringkas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-500/10 rounded-lg mr-3">
                            <i class="mdi mdi-account-card-details text-blue-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Profil Anak</h3>
                    </div>
                </div>
                <div class="p-6 space-y-4 text-sm text-gray-600">
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">Nama Anak:</span>
                        <span class="text-gray-900 font-bold text-right">{{ $bayiBalita->penduduk->nama }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">NIK:</span>
                        <span class="text-gray-900 font-mono">{{ $bayiBalita->penduduk->nik }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">Tanggal Lahir:</span>
                        <span class="text-gray-900">{{ \Carbon\Carbon::parse($bayiBalita->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">Umur Saat Ini:</span>
                        <span class="text-gray-900 font-bold text-blue-600">{{ $bayiBalita->umur_bulan }} Bulan</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">Jenis Kelamin:</span>
                        <span class="text-gray-900 capitalize">{{ $bayiBalita->penduduk->kelamin }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">Nama Ibu:</span>
                        <span class="text-gray-900">{{ $bayiBalita->nama_ibu ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-gray-50">
                        <span class="font-semibold">Posyandu:</span>
                        <span class="text-gray-900">{{ $bayiBalita->posyandu->nama ?? '-' }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Row 2: Tabel Riwayat Vaksinasi / Imunisasi (FULL WIDTH) -->
    <div class="w-full">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/30">
                <form id="imunisasi-filter-form" action="{{ route('bayi-balitas.imunisasi', $bayiBalita) }}" method="GET">
                    <input type="hidden" name="sort" value="{{ request('sort', 'tanggal_pemberian') }}">
                    <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center">
                            <div class="p-2.5 bg-green-500/10 rounded-lg mr-3 flex-shrink-0">
                                <i class="mdi mdi-history text-green-600 text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 whitespace-nowrap">Riwayat Vaksinasi (Imunisasi)</h3>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Search Input -->
                            <div class="relative flex-1 min-w-[220px] sm:w-72">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 pointer-events-none">
                                    <i class="mdi mdi-magnify text-lg"></i>
                                </span>
                                <input type="text" name="search" id="imunisasi-search-input" value="{{ request('search') }}" placeholder="Cari jenis vaksin, tanggal, batch..." 
                                    class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none transition-all">
                            </div>

                            <!-- Per Page Dropdown -->
                            <div class="flex items-center gap-2">
                                <label class="text-xs font-semibold text-gray-500 whitespace-nowrap">Tampilkan:</label>
                                <select name="per_page" onchange="this.form.submit()" 
                                    class="bg-white border border-gray-200 rounded-xl text-sm px-3 py-2 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none appearance-none pr-8 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1rem_1rem] bg-[right_0.5rem_center] bg-no-repeat">
                                    @foreach([5, 10, 25, 50] as $size)
                                        <option value="{{ $size }}" {{ request('per_page', 5) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors group"
                                onclick="updateImunisasiSort('nama_vaksin')">
                                <div class="flex items-center gap-1">
                                    Jenis Vaksin
                                    @if(request('sort') == 'nama_vaksin')
                                        <i class="mdi mdi-arrow-{{ request('direction', 'desc') == 'asc' ? 'up' : 'down' }} text-green-600"></i>
                                    @else
                                        <i class="mdi mdi-arrow-up-down text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    @endif
                                </div>
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors group"
                                onclick="updateImunisasiSort('tanggal_pemberian')">
                                <div class="flex items-center gap-1">
                                    Tanggal Pemberian
                                    @if(request('sort', 'tanggal_pemberian') == 'tanggal_pemberian')
                                        <i class="mdi mdi-arrow-{{ request('direction', 'desc') == 'asc' ? 'up' : 'down' }} text-green-600"></i>
                                    @else
                                        <i class="mdi mdi-arrow-up-down text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    @endif
                                </div>
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors group"
                                onclick="updateImunisasiSort('keterangan')">
                                <div class="flex items-center gap-1">
                                    Keterangan / No. Batch
                                    @if(request('sort') == 'keterangan')
                                        <i class="mdi mdi-arrow-{{ request('direction', 'desc') == 'asc' ? 'up' : 'down' }} text-green-600"></i>
                                    @else
                                        <i class="mdi mdi-arrow-up-down text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                    @endif
                                </div>
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-150">
                        @forelse($imunisasiHistory as $imun)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                    <div class="flex items-center gap-2">
                                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                        {{ $imun->nama_vaksin }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                    {{ \Carbon\Carbon::parse($imun->tanggal_pemberian)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $imun->keterangan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" 
                                            onclick='openEditImunisasiModal(@json($imun))' 
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                            title="Edit Imunisasi">
                                            <i class="mdi mdi-pencil text-lg"></i>
                                        </button>
                                        <form action="{{ route('imunisasis.destroy', $imun) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus riwayat imunisasi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                <i class="mdi mdi-delete text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="mdi mdi-needle text-5xl text-gray-200"></i>
                                        <p class="mt-2 text-gray-400 font-medium">Belum ada riwayat imunisasi yang dicatat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100 bg-gray-50/30">
                {{ $imunisasiHistory->links() }}
            </div>
        </div>
    </div>

    <!-- Edit Imunisasi Modal -->
    <div id="edit-imunisasi-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeEditImunisasiModal()"></div>

        <!-- Modal Container -->
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-green-500/10 rounded-lg mr-3">
                            <i class="mdi mdi-pencil text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Edit Riwayat Imunisasi</h3>
                            <p class="text-xs text-gray-500">Perbarui data pemberian vaksinasi anak</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeEditImunisasiModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>

                <!-- Form -->
                <form id="edit-imunisasi-form" action="" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Vaksin <span class="text-red-500">*</span></label>
                            <input type="text" name="imunisasi_nama_vaksin" id="edit_imunisasi_nama_vaksin" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pemberian <span class="text-red-500">*</span></label>
                            <input type="date" name="imunisasi_tanggal_pemberian" id="edit_imunisasi_tanggal_pemberian" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan / Nomor Batch</label>
                            <input type="text" name="imunisasi_keterangan" id="edit_imunisasi_keterangan" placeholder="Contoh: Batch No. 9812A"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-green-500/10 focus:border-green-500 outline-none">
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" onclick="closeEditImunisasiModal()"
                            class="px-5 py-2.5 bg-white text-gray-700 font-semibold rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/15 transition flex items-center gap-2">
                            <i class="mdi mdi-content-save"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditImunisasiModal(imun) {
            const form = document.getElementById('edit-imunisasi-form');
            form.action = `/imunisasis/${imun.id}`;

            document.getElementById('edit_imunisasi_nama_vaksin').value = imun.nama_vaksin || '';
            document.getElementById('edit_imunisasi_tanggal_pemberian').value = imun.tanggal_pemberian || '';
            document.getElementById('edit_imunisasi_keterangan').value = imun.keterangan || '';

            document.getElementById('edit-imunisasi-modal').classList.remove('hidden');
        }

        function closeEditImunisasiModal() {
            document.getElementById('edit-imunisasi-modal').classList.add('hidden');
        }

        function updateImunisasiSort(field) {
            const form = document.getElementById('imunisasi-filter-form');
            if (!form) return;
            const currentField = form.sort.value;
            const currentDirection = form.direction.value;

            if (currentField === field) {
                form.direction.value = currentDirection === 'asc' ? 'desc' : 'asc';
            } else {
                form.sort.value = field;
                form.direction.value = 'asc';
            }
            form.submit();
        }

        let imunisasiSearchTimeout;
        const imunisasiSearchInput = document.getElementById('imunisasi-search-input');
        if (imunisasiSearchInput) {
            imunisasiSearchInput.addEventListener('input', function() {
                clearTimeout(imunisasiSearchTimeout);
                imunisasiSearchTimeout = setTimeout(() => {
                    document.getElementById('imunisasi-filter-form').submit();
                }, 500);
            });
        }
    </script>
</x-layout>
