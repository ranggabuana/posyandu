<x-layout title="Data Kader Posyandu">
    <x-page-header 
        title="Daftar Kader Posyandu"
        subtitle="Kelola petugas dan kader posyandu pelayanan kesehatan di Desa Banjar"
        icon="mdi-account-star"
        :breadcrumbs="[
            'Pengaturan' => null,
            'Data Kader' => null
        ]"
    >
        @if(auth()->user()->hasRole('posyandu'))
            <a id="export-excel" href="{{ route('kaders.export', request()->query()) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-xs">
                <i class="mdi mdi-file-excel text-sm"></i> <span>Export Excel</span>
            </a>
        @else
            <button type="button" onclick="openExportModal()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-xs cursor-pointer">
                <i class="mdi mdi-file-excel text-sm"></i> <span>Export Excel</span>
            </button>
        @endif
        <a href="{{ route('kaders.create', ['posyandu_id' => request('posyandu_id')]) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-xs">
            <i class="mdi mdi-plus text-sm"></i> <span>Tambah Kader</span>
        </a>
    </x-page-header>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl text-sm flex items-center shadow-sm">
        <i class="mdi mdi-check-circle mr-2 text-lg"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50/50">
            <form id="filter-form" action="{{ route('kaders.index') }}" method="GET" class="w-full">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="relative flex-1 w-full sm:w-auto min-w-0 sm:min-w-[300px]">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="mdi mdi-magnify text-xl"></i>
                        </span>
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari kader berdasarkan nama atau NIK..." 
                            class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    </div>

                    @if(!auth()->user()->hasRole('posyandu'))
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-500 whitespace-nowrap">Posyandu:</label>
                        <select name="posyandu_id" onchange="this.form.submit()" 
                            class="bg-white border border-gray-300 rounded-xl text-sm px-3 py-2 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none pr-8 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_0.5rem_center] bg-no-repeat">
                            <option value="">Semua Posyandu</option>
                            @foreach($posyandus as $p)
                                <option value="{{ $p->id }}" {{ request('posyandu_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-500 whitespace-nowrap">Tampilkan:</label>
                        <select name="per_page" onchange="this.form.submit()" 
                            class="bg-white border border-gray-300 rounded-xl text-sm px-3 py-2 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none pr-8 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_0.5rem_center] bg-no-repeat">
                            @foreach([10, 15, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ request('per_page') == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <input type="hidden" name="sort" value="{{ request('sort', 'kaders.created_at') }}">
                <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        @php
                            $columns = [
                                'penduduk.nama' => 'Kader / NIK',
                                'jabatan' => 'Jabatan',
                                'tanggal_mulai_tugas' => 'Mulai Tugas',
                            ];
                            
                            if (!auth()->user()->hasRole('posyandu')) {
                                $columns['posyandu_id'] = 'Posyandu';
                            }
                            
                            $columns['status_aktif'] = 'Status';
                        @endphp

                        @foreach($columns as $field => $label)
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors group"
                            onclick="updateSort('{{ $field }}')">
                            <div class="flex items-center gap-1">
                                {{ $label }}
                                @if(request('sort') == $field)
                                    <i class="mdi mdi-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} text-blue-500"></i>
                                @else
                                    <i class="mdi mdi-arrow-up-down text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                @endif
                            </div>
                        </th>
                        @endforeach
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pelatihan & Keterangan</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kaders as $item)
                    <tr class="hover:bg-blue-50/30 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $item->penduduk->nama }}</div>
                            <div class="text-xs text-gray-500">NIK: {{ $item->penduduk->nik }}</div>
                            @if($item->penduduk->telepon)
                            <div class="text-xs text-gray-400 mt-0.5"><i class="mdi mdi-phone text-[10px]"></i> {{ $item->penduduk->telepon }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2.5 py-0.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg border border-blue-150">
                                {{ $item->jabatan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->tanggal_mulai_tugas ? $item->tanggal_mulai_tugas->format('d-m-Y') : '-' }}
                        </td>
                        @if(!auth()->user()->hasRole('posyandu'))
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->posyandu->nama }}
                        </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $item->status_aktif ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $item->status_aktif ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-[250px]">
                            @if($item->pelatihan)
                            <div class="truncate text-xs font-medium text-purple-700 bg-purple-50 px-2 py-0.5 rounded border border-purple-100 mb-1" title="{{ $item->pelatihan }}">
                                Pelatihan: {{ $item->pelatihan }}
                            </div>
                            @endif
                            @if($item->keterangan)
                            <div class="truncate text-xs text-gray-500" title="{{ $item->keterangan }}">
                                {{ $item->keterangan }}
                            </div>
                            @else
                            <span class="text-gray-300 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('kaders.edit', $item) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <i class="mdi mdi-pencil text-xl"></i>
                                </a>
                                <form action="{{ route('kaders.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kader ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <i class="mdi mdi-delete text-xl"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="mdi mdi-database-off text-6xl text-gray-200"></i>
                                <p class="mt-2 text-gray-500">Tidak ada data ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 bg-gray-50/30">
            {{ $kaders->links() }}
        </div>
    </div>

    <script>
        function updateSort(field) {
            const form = document.getElementById('filter-form');
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

        // Debounce search
        let searchTimeout;
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 500);
        });
    </script>

    @if(!auth()->user()->hasRole('posyandu'))
    <!-- Export Excel Modal -->
    <div id="export-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <!-- Backdrop overlay -->
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeExportModal()"></div>
        
        <!-- Modal container -->
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div class="flex items-center flex-row">
                        <div class="p-2 bg-emerald-500/10 rounded-lg mr-3 flex items-center justify-center">
                            <i class="mdi mdi-file-excel text-emerald-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Export Excel Data Kader</h3>
                    </div>
                    <button type="button" onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>
                
                <!-- Form -->
                <form action="{{ route('kaders.export') }}" method="GET">
                    <div class="p-6 space-y-4">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div>
                            <label for="export_posyandu_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Posyandu</label>
                            <select name="posyandu_id" id="export_posyandu_id"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all duration-200">
                                <option value="">Semua Data Kader (Seluruh Posyandu)</option>
                                @foreach($posyandus as $p)
                                    <option value="{{ $p->id }}" {{ request('posyandu_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-2xl">
                        <button type="button" onclick="closeExportModal()"
                            class="px-4 py-2 bg-white text-gray-700 font-semibold rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" onclick="closeExportModal()"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-lg shadow-emerald-600/20 transition active:scale-[0.98] flex items-center justify-center gap-2">
                            <i class="mdi mdi-download"></i>
                            Export Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openExportModal() {
            document.getElementById('export-modal').classList.remove('hidden');
        }

        function closeExportModal() {
            document.getElementById('export-modal').classList.add('hidden');
        }
    </script>
    @endif
</x-layout>
