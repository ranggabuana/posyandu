
<x-layout title="Data Ibu Hamil">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Data Ibu Hamil</h2>
        <div class="flex space-x-2">
            <a id="export-excel" href="{{ route('ibu-hamils.export') }}" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition flex items-center gap-2">
                <i class="mdi mdi-file-excel"></i> <span>Export Excel</span>
            </a>
            <a href="{{ route('ibu-hamils.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition flex items-center gap-2">
                <i class="mdi mdi-plus"></i> Tambah Data
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50/50">
            <form id="filter-form" action="{{ route('ibu-hamils.index') }}" method="GET" class="w-full">
                <div class="flex flex-wrap items-center gap-4 mb-4">
                    <div class="relative flex-1 min-w-[300px]">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="mdi mdi-magnify text-xl"></i>
                        </span>
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari berdasarkan nama penduduk..." 
                            class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    </div>
                    
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

                <x-hierarchical-filter 
                    :route="route('ibu-hamils.index')" 
                    :dusuns="$dusuns" 
                    :rws="$rws" 
                    :rts="$rts" 
                    :showKelamin="false"
                />

                <input type="hidden" name="sort" value="{{ request('sort', 'ibu_hamils.created_at') }}">
                <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        @php
                            $columns = [
                                'penduduk.nama' => 'Nama Ibu',
                                'usia_kehamilan_minggu' => 'Usia (Minggu)',
                                'taksiran_persalinan' => 'Taksiran Persalinan',
                                'status' => 'Status',
                            ];
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
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ibuHamils as $item)
                    <tr class="hover:bg-blue-50/30 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $item->penduduk->nama }}</div>
                            <div class="text-xs text-gray-500">NIK: {{ $item->penduduk->nik }}</div>
                            @if($item->faktor_resiko)
                                <span class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 rounded-md">
                                    <i class="mdi mdi-alert text-[10px]"></i>
                                    {{ $item->faktor_resiko }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->usia_kehamilan_minggu }} Minggu</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->taksiran_persalinan ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $item->status == 'aktif' ? 'bg-green-100 text-green-700' : ($item->status == 'selesai' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('ibu-hamils.pemeriksaan', $item) }}" class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Pemeriksaan">
                                    <i class="mdi mdi-clipboard-pulse text-xl"></i>
                                </a>
                                <a href="{{ route('ibu-hamils.edit', $item) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <i class="mdi mdi-pencil text-xl"></i>
                                </a>
                                <form action="{{ route('ibu-hamils.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
            {{ $ibuHamils->links() }}
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

        // Real-time search with debounce
        let searchTimeout;
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 500); // 500ms delay
        });

        // Export loading animation
        const exportBtn = document.getElementById('export-excel');
        if (exportBtn) {
            exportBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const btn = this;
                const originalContent = btn.innerHTML;
                
                // Show loading state
                btn.classList.add('opacity-75', 'cursor-wait');
                btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> <span>Memproses...</span>';
                btn.style.pointerEvents = 'none';

                // Get filter values from the form
                const form = document.getElementById('filter-form');
                const formData = new FormData(form);
                const params = new URLSearchParams();
                
                // Collect all non-empty values
                for (const [key, value] of formData.entries()) {
                    if (value && value !== '') params.append(key, value);
                }
                
                // Construct export URL with current filters
                const baseUrl = btn.getAttribute('href').split('?')[0];
                const exportUrl = baseUrl + "?" + params.toString();
                
                // Start download
                window.location.href = exportUrl;
                
                // Reset button after a delay
                setTimeout(() => {
                    btn.classList.remove('opacity-75', 'cursor-wait');
                    btn.innerHTML = originalContent;
                    btn.style.pointerEvents = 'auto';
                }, 5000);
            });
        }
    </script>
</x-layout>
