<x-layout title="Daftar Admin">
    <x-page-header 
        title="Manajemen User Admin"
        subtitle="Kelola akun pengguna admin dan hak akses sistem posyandu"
        icon="mdi-account-key"
        :breadcrumbs="[
            'Pengaturan' => null,
            'Data User Admin' => null
        ]"
    >
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs transition flex items-center gap-2 shadow-xs">
            <i class="mdi mdi-plus text-sm"></i> <span>Tambah Admin</span>
        </a>
    </x-page-header>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50/50">
            <form id="filter-form" action="{{ route('admin.users.index') }}" method="GET" class="w-full">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="relative flex-1 w-full sm:w-auto min-w-0 sm:min-w-[300px]">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="mdi mdi-magnify text-xl"></i>
                        </span>
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari admin berdasarkan nama, username, atau email..." 
                            class="w-full pl-10 pr-4 py-2 bg-white border border-gray-300 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all">
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Username</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $item)
                    <tr class="hover:bg-blue-50/30 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $item->name }}</div>
                            @if($item->id === auth()->id())
                                <span class="px-2 py-0.5 bg-green-100 text-green-750 text-[10px] font-bold rounded-full">Akun Anda</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->username }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->email ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $item) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <i class="mdi mdi-pencil text-xl"></i>
                                </a>
                                @if($item->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus admin ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <i class="mdi mdi-delete text-xl"></i>
                                    </button>
                                </form>
                                @endif
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
            {{ $users->links() }}
        </div>
    </div>

    <script>
        // Real-time search with debounce
        let searchTimeout;
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 500);
        });
    </script>
</x-layout>
