<x-layout title="Data Remaja">
    <x-page-header 
        title="Data Remaja"
        subtitle="Daftar pemantauan penduduk remaja usia {{ $minAge }} hingga {{ $maxAge }} tahun (Posyandu Remaja)"
        icon="mdi-account-school"
        :breadcrumbs="[
            'Data Kesehatan' => null,
            'Data Remaja' => null
        ]"
    >
        <a id="export-excel" href="{{ route('remajas.export') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-xs">
            <i class="mdi mdi-file-excel text-sm"></i> <span>Export Excel</span>
        </a>
    </x-page-header>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="p-6">
            <form id="filter-form" action="{{ route('remajas.index') }}" method="GET" class="space-y-4">
                <div class="flex flex-col md:flex-row justify-between gap-4 mb-4">
                    <div class="relative flex-1 w-full sm:w-auto min-w-0 sm:min-w-[300px]">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="mdi mdi-magnify text-xl"></i>
                        </span>
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari nama atau NIK..." 
                            class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-500 whitespace-nowrap">Tampilkan:</label>
                        <select name="per_page" onchange="this.form.submit()" 
                            class="bg-gray-50 border border-gray-300 rounded-xl text-sm px-3 py-2 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none pr-8 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_0.5rem_center] bg-no-repeat">
                            @foreach([10, 15, 25, 50, 100] as $size)
                                <option value="{{ $size }}" {{ request('per_page') == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <x-hierarchical-filter 
                    :route="route('remajas.index')" 
                    :dusuns="$dusuns" 
                    :rws="$rws" 
                    :rts="$rts" 
                    :showKelamin="true"
                />

                <input type="hidden" name="sort" value="{{ request('sort', 'nama') }}">
                <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        @php
                            $columns = [
                                'nama' => 'Nama',
                                'nik' => 'NIK',
                                'kelamin' => 'Jenis Kelamin',
                                'umur' => 'Umur',
                                'dusun' => 'Wilayah',
                                'telepon' => 'Telepon',
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
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($remajas as $item)
                    <tr class="hover:bg-blue-50/30 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $item->nama }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">{{ $item->nik }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $isLaki = in_array(strtolower($item->kelamin ?? ''), ['laki-laki', 'l']);
                            @endphp
                            <span class="px-2.5 py-0.5 font-bold text-[11px] rounded-full border inline-flex items-center gap-1 {{ $isLaki ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-pink-50 text-pink-700 border-pink-100' }}">
                                {{ $isLaki ? 'Laki-Laki' : 'Perempuan' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-semibold">{{ $item->umur }} Tahun</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs text-gray-900">{{ $item->dusun ?? '-' }}</div>
                            <div class="text-[10px] text-gray-500">RW {{ $item->rw ?? '00' }} / RT {{ $item->rt ?? '00' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->telepon ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <a href="{{ route('remajas.pemeriksaan', $item->id) }}" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-xl text-xs font-semibold transition-all duration-200 border border-blue-100 shadow-2xs">
                                <i class="mdi mdi-heart-pulse text-sm"></i>
                                <span>Pemeriksaan</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="mdi mdi-database-off text-6xl text-gray-200"></i>
                                <p class="mt-2 text-sm text-gray-500 font-medium">Tidak ada data remaja yang ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($remajas->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $remajas->links() }}
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function updateSort(field) {
            const currentSort = "{{ request('sort', 'nama') }}";
            const currentDirection = "{{ request('direction', 'asc') }}";
            
            let newDirection = 'asc';
            if (currentSort === field) {
                newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
            }
            
            const form = document.getElementById('filter-form');
            form.querySelector('input[name="sort"]').value = field;
            form.querySelector('input[name="direction"]').value = newDirection;
            form.submit();
        }
    </script>
    @endpush
</x-layout>
