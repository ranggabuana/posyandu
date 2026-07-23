<x-layout title="Jadwal Pelayanan">
    <x-page-header 
        title="Jadwal Pelayanan Posyandu"
        subtitle="Kelola jadwal kegiatan dan agenda pelayanan kesehatan rutin"
        icon="mdi-calendar-clock"
        :breadcrumbs="[
            'Konten' => null,
            'Jadwal Pelayanan' => null
        ]"
    >
        <a href="{{ route('jadwals.export') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-xs">
            <i class="mdi mdi-file-excel text-sm"></i> <span>Export Excel</span>
        </a>
        <a href="{{ route('jadwals.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-xs">
            <i class="mdi mdi-plus text-sm"></i> <span>Tambah Jadwal</span>
        </a>
    </x-page-header>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50/50">
            <form id="filter-form" action="{{ route('jadwals.index') }}" method="GET" class="w-full">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="relative flex-1 w-full sm:w-auto min-w-0 sm:min-w-[300px]">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="mdi mdi-magnify text-xl"></i>
                        </span>
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari berdasarkan hari/tanggal, kegiatan, atau keterangan..." 
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

                <input type="hidden" name="sort" value="{{ request('sort', 'created_at') }}">
                <input type="hidden" name="direction" value="{{ request('direction', 'desc') }}">
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        @php
                            $columns = [
                                'hari_tanggal' => 'Hari / Tanggal',
                                'jam_mulai' => 'Jam',
                                'kegiatan' => 'Kegiatan',
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
                    @forelse($jadwals as $item)
                    <tr class="hover:bg-blue-50/30 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $item->hari_tanggal }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->jam_mulai }} {{ $item->jam_selesai ? '- ' . $item->jam_selesai : '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $item->kegiatan }}</div>
                            @if($item->keterangan)
                                <div class="text-xs text-gray-500">{{ Str::limit($item->keterangan, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('jadwals.edit', $item) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <i class="mdi mdi-pencil text-xl"></i>
                                </a>
                                <form action="{{ route('jadwals.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="mdi mdi-calendar-blank text-4xl mb-2 text-gray-300"></i>
                                <p class="text-base font-medium">Belum ada data jadwal</p>
                                <p class="text-sm mt-1">Tambahkan data jadwal baru untuk menampilkannya di sini dan di halaman publik.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $jadwals->links() }}
        </div>
    </div>
</x-layout>

@push('scripts')
<script>
    function updateSort(field) {
        const form = document.getElementById('filter-form');
        const sortInput = form.querySelector('input[name="sort"]');
        const dirInput = form.querySelector('input[name="direction"]');

        if (sortInput.value === field) {
            dirInput.value = dirInput.value === 'asc' ? 'desc' : 'asc';
        } else {
            sortInput.value = field;
            dirInput.value = 'asc';
        }

        form.submit();
    }
    
    // Auto submit search on delay
    let timeout = null;
    document.getElementById('search-input').addEventListener('keyup', function(e) {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            document.getElementById('filter-form').submit();
        }, 500);
    });
</script>
@endpush
