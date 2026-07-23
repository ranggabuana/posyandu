
<x-layout title="Data Bayi">
    <x-page-header 
        title="Data Bayi (0-12 Bulan)"
        subtitle="Daftar pemantauan dan rekam medis bayi usia 0 hingga 12 bulan"
        icon="mdi-baby-face-outline"
        :breadcrumbs="[
            'Data Kesehatan' => null,
            'Data Bayi' => null
        ]"
    >
        <a id="export-excel" href="{{ route('bayi-balitas.export') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-xs">
            <i class="mdi mdi-file-excel text-sm"></i> <span>Export Excel</span>
        </a>
        <a href="{{ route('bayi-balitas.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-xs">
            <i class="mdi mdi-plus text-sm"></i> <span>Tambah Bayi</span>
        </a>
    </x-page-header>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50/50">
            <form id="filter-form" action="{{ route('bayi-balitas.index') }}" method="GET" class="w-full">
                <div class="flex flex-wrap items-center gap-4 mb-4">
                    <div class="relative flex-1 w-full sm:w-auto min-w-0 sm:min-w-[300px]">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="mdi mdi-magnify text-xl"></i>
                        </span>
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari berdasarkan nama bayi atau nama ibu..." 
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
                    :route="route('bayi-balitas.index')" 
                    :dusuns="$dusuns" 
                    :rws="$rws" 
                    :rts="$rts" 
                    :showKelamin="true"
                />

                <input type="hidden" name="sort" value="{{ request('sort', 'bayi_balitas.created_at') }}">
                <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        @php
                            $columns = [
                                'penduduk.nama' => 'Nama Bayi',
                                'nama_ibu' => 'Nama Ibu',
                                'tanggal_lahir' => 'Tgl Lahir',
                                'posyandu.nama' => 'Posyandu',
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
                    @forelse($bayiBalitas as $item)
                    <tr class="hover:bg-blue-50/30 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900 flex items-center gap-1.5">
                                <span>{{ $item->penduduk->nama }}</span>
                                @if($item->is_2t)
                                    <span class="px-2 py-0.5 bg-red-100 text-red-800 border border-red-200 font-bold rounded-md text-[10px] flex items-center gap-1" title="Peringatan: 2 Kali Tidak Naik BB (Perlu Rujukan)">
                                        <i class="mdi mdi-alert-circle text-xs text-red-600"></i> 2T
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500">NIK: {{ $item->penduduk->nik }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->nama_ibu ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->tanggal_lahir }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->posyandu->nama ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-1.5">
                                <a href="{{ route('bayi-balitas.pemeriksaan', $item) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Penimbangan Bulanan">
                                    <i class="mdi mdi-scale-bathroom text-xl"></i>
                                </a>
                                <a href="{{ route('bayi-balitas.imunisasi', $item) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Catat Imunisasi">
                                    <i class="mdi mdi-needle text-xl"></i>
                                </a>
                                <a href="{{ route('bayi-balitas.edit', $item) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                    <i class="mdi mdi-pencil text-xl"></i>
                                </a>
                                <form action="{{ route('bayi-balitas.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
            {{ $bayiBalitas->links() }}
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
