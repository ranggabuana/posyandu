<x-layout title="Data PUS">
    <x-page-header 
        title="Data Pasangan Usia Subur (PUS)"
        subtitle="Daftar pasangan suami istri dengan rentang usia subur"
        icon="mdi-account-group"
        :breadcrumbs="[
            'Data Kesehatan' => null,
            'Data PUS' => null
        ]"
    >
        <a id="export-excel" href="{{ route('puses.export') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-xs">
            <i class="mdi mdi-file-excel text-sm"></i> <span>Export Excel</span>
        </a>
        <a href="{{ route('puses.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-xs">
            <i class="mdi mdi-plus text-sm"></i> <span>Tambah PUS</span>
        </a>
    </x-page-header>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="p-6">
            <form id="filter-form" action="{{ route('puses.index') }}" method="GET" class="space-y-4">
                <div class="flex flex-col md:flex-row justify-between gap-4 mb-4">
                    <div class="relative flex-1 w-full sm:w-auto min-w-0 sm:min-w-[300px]">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="mdi mdi-magnify text-xl"></i>
                        </span>
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari nama suami atau istri..." 
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
                    :route="route('puses.index')" 
                    :dusuns="$dusuns" 
                    :rws="$rws" 
                    :rts="$rts" 
                    :showKelamin="false"
                />
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Pasangan
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Posyandu
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Keterangan
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($puses as $item)
                    <tr class="hover:bg-blue-50/30 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                <i class="mdi mdi-human-male text-blue-500"></i> {{ $item->suami->nama ?? '-' }} 
                            </div>
                            <div class="text-sm font-semibold text-gray-900 flex items-center gap-2 mt-1">
                                <i class="mdi mdi-human-female text-pink-500"></i> {{ $item->istri->nama ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->posyandu->nama ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->keterangan ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('puses.edit', $item) }}" class="text-yellow-500 hover:text-yellow-600 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-lg transition mr-2 inline-block">
                                <i class="mdi mdi-pencil text-lg"></i>
                            </a>
                            <form action="{{ route('puses.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition">
                                    <i class="mdi mdi-delete text-lg"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="mdi mdi-account-group-outline text-6xl text-gray-200"></i>
                                <p class="mt-2 text-gray-500">Tidak ada data PUS ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 bg-gray-50/30">
            {{ $puses->links() }}
        </div>
    </div>

    <script>
        let searchTimeout;
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 500);
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
