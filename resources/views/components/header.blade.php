@props([
    'breadcrumbs' => []
])

<header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
    <div class="flex items-center">
        <button id="sidebar-toggle-btn" class="mr-4 text-gray-500 lg:hidden">
            <i class="mdi mdi-menu text-xl"></i>
        </button>
        <button id="sidebar-collapse-btn" class="mr-4 text-gray-500 hidden lg:block">
            <i class="mdi mdi-chevron-left text-xl"></i>
        </button>

        <!-- Dynamic Top Nav Breadcrumb -->
        <nav aria-label="Breadcrumb" class="ml-1 sm:ml-4 breadcrumb-nav">
            <ol class="flex items-center space-x-1 sm:space-x-1.5 text-xs sm:text-sm">
                <li>
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 transition-colors flex items-center gap-1 font-semibold">
                        <i class="mdi mdi-home text-sm"></i>
                        <span>Beranda</span>
                    </a>
                </li>
                
                @php
                    $crumbs = $breadcrumbs;
                    // Auto fallback if crumbs empty and not on dashboard
                    if (empty($crumbs) && Route::currentRouteName() !== 'dashboard') {
                        $routeName = Route::currentRouteName();
                        $routeLabel = match($routeName) {
                            'penduduks.index' => 'Data Penduduk',
                            'penduduks.create' => 'Tambah Penduduk',
                            'penduduks.edit' => 'Edit Penduduk',
                            'ibus.index' => 'Data Ibu',
                            'ibu-hamils.index' => 'Data Ibu Hamil',
                            'ibu-hamils.pemeriksaan' => 'Pemeriksaan Ibu Hamil',
                            'bayi-balitas.index' => 'Data Bayi (0-12 Bulan)',
                            'balitas.index' => 'Data Balita (13-60 Bulan)',
                            'bayi-balitas.pemeriksaan' => 'Pemeriksaan Balita',
                            'remajas.index' => 'Data Remaja',
                            'remajas.pemeriksaan' => 'Pemeriksaan Remaja',
                            'lansias.index' => 'Data Lansia',
                            'lansias.pemeriksaan' => 'Pemeriksaan Lansia',
                            'wuses.index' => 'Data WUS',
                            'puses.index' => 'Data PUS',
                            'posyandus.index' => 'Data Posyandu',
                            'kaders.index' => 'Data Kader',
                            'tims.index' => 'Tim Posyandu',
                            'jadwals.index' => 'Jadwal Pelayanan',
                            'beritas.index' => 'Berita & Artikel',
                            'galeries.index' => 'Galeri Foto',
                            'buku-tamus.index' => 'Buku Tamu',
                            'laporan-masyarakats.index' => 'Laporan Warga',
                            'pengaturans.index' => 'Pengaturan Sistem',
                            'profile.edit' => 'Profil Saya',
                            default => Str::title(str_replace(['.', '-'], ' ', $routeName ?? 'Detail'))
                        };

                        if (in_array($routeName, ['penduduks.index', 'penduduks.create', 'penduduks.edit', 'ibus.index'])) {
                            $crumbs = ['Data Kependudukan' => null, $routeLabel => null];
                        } elseif (in_array($routeName, ['ibu-hamils.index', 'bayi-balitas.index', 'balitas.index', 'remajas.index', 'lansias.index', 'wuses.index', 'puses.index', 'ibu-hamils.pemeriksaan', 'bayi-balitas.pemeriksaan', 'remajas.pemeriksaan', 'lansias.pemeriksaan'])) {
                            $crumbs = ['Data Kesehatan' => null, $routeLabel => null];
                        } elseif (in_array($routeName, ['posyandus.index', 'kaders.index', 'tims.index', 'jadwals.index', 'beritas.index', 'galeries.index'])) {
                            $crumbs = ['Pengelolaan Posyandu' => null, $routeLabel => null];
                        } elseif (in_array($routeName, ['buku-tamus.index', 'laporan-masyarakats.index'])) {
                            $crumbs = ['Interaksi Warga' => null, $routeLabel => null];
                        } else {
                            $crumbs = [$routeLabel => null];
                        }
                    }
                @endphp

                @if(is_array($crumbs) && count($crumbs) > 0)
                    @foreach($crumbs as $label => $link)
                        <li class="flex items-center">
                            <svg class="w-3.5 h-3.5 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            @if($link)
                                <a href="{{ $link }}" class="text-blue-600 hover:text-blue-800 transition-colors font-medium">
                                    {{ $label }}
                                </a>
                            @else
                                <span class="text-gray-700 font-semibold">
                                    {{ $label }}
                                </span>
                            @endif
                        </li>
                    @endforeach
                @endif
            </ol>
        </nav>
    </div>
    <div class="flex items-center space-x-2 sm:space-x-4">
        @php
            $unprocessedReports = \App\Models\LaporanMasyarakat::where('status', 'baru')
                ->latest()
                ->take(5)
                ->get();
            $unprocessedCount = \App\Models\LaporanMasyarakat::where('status', 'baru')->count();
        @endphp
        <div class="relative">
            <button id="notification-btn"
                class="p-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 hover:text-gray-900 transition-colors relative cursor-pointer">
                <i class="mdi mdi-bell text-lg"></i>
                @if($unprocessedCount > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold rounded-full h-4.5 w-4.5 flex items-center justify-center animate-bounce shadow-sm">
                        {{ $unprocessedCount > 9 ? '9+' : $unprocessedCount }}
                    </span>
                @endif
            </button>

            <!-- Notification Dropdown -->
            <div id="notification-dropdown"
                class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl py-2 z-20 border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Aduan Belum Ditindaklanjuti</h3>
                    @if($unprocessedCount > 0)
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-red-50 text-red-600 rounded-full">
                            {{ $unprocessedCount }} Baru
                        </span>
                    @endif
                </div>
                
                <div class="max-h-64 overflow-y-auto">
                    @forelse($unprocessedReports as $laporan)
                        @php
                            $kategoriConfig = [
                                'pelayanan' => ['bg' => 'bg-indigo-50 text-indigo-600', 'icon' => 'mdi-face-agent'],
                                'infrastruktur' => ['bg' => 'bg-blue-50 text-blue-600', 'icon' => 'mdi-office-building'],
                                'kesehatan' => ['bg' => 'bg-teal-50 text-teal-600', 'icon' => 'mdi-medical-bag'],
                                'lainnya' => ['bg' => 'bg-gray-50 text-gray-600', 'icon' => 'mdi-folder-information'],
                            ];
                            $kat = $kategoriConfig[strtolower($laporan->kategori ?? 'lainnya')] ?? $kategoriConfig['lainnya'];
                        @endphp
                        <a href="{{ route('laporan-masyarakats.edit', $laporan) }}"
                            class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-50 transition-colors">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 pt-0.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $kat['bg'] }}">
                                        <i class="mdi {{ $kat['icon'] }} text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1 min-w-0">
                                    <p class="text-xs font-bold text-gray-900 truncate">{{ $laporan->nama_pelapor }}</p>
                                    <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-1 leading-relaxed">{{ $laporan->isi_laporan }}</p>
                                    <p class="text-[9px] text-gray-400 font-medium mt-1"><i class="mdi mdi-clock-outline mr-0.5"></i>{{ $laporan->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="px-4 py-8 text-center text-gray-500">
                            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-2 border border-gray-100">
                                <i class="mdi mdi-bell-off text-lg text-gray-400"></i>
                            </div>
                            <p class="text-xs font-bold text-gray-700">Semua Terkendali!</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">Tidak ada laporan baru yang belum ditindaklanjuti.</p>
                        </div>
                    @endforelse
                </div>
                
                <a href="{{ route('laporan-masyarakats.index') }}"
                    class="block px-4 py-2 text-center text-xs font-bold text-teal-600 hover:text-teal-700 bg-gray-50 hover:bg-gray-100/80 rounded-b-md transition-colors border-t border-gray-100">
                    Lihat Semua Laporan
                </a>
            </div>
        </div>
        <div class="relative">
            <button id="user-menu-button" class="flex items-center space-x-2 focus:outline-none cursor-pointer">
                <div
                    class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white sm:w-10 sm:h-10">
                    <span class="font-semibold text-xs sm:text-sm">{{ substr(Auth::user()->name ?? 'AU', 0, 1) }}</span>
                </div>
                <span
                    class="text-gray-700 font-medium hidden sm:inline-block">{{ Auth::user()->name ?? 'User' }}</span>
                <i class="mdi mdi-chevron-down text-gray-500 hidden sm:inline-block"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="user-dropdown"
                class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl py-2 z-20 border border-gray-200 overflow-hidden opacity-0 invisible">
                <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-sm font-medium text-gray-900">
                        {{ Auth::user()->name ?? 'Admin User' }}</p>
                    <p class="text-xs text-gray-500 truncate">
                        {{ Auth::user()->email ?? 'email@example.com' }}</p>
                </div>
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    <i class="mdi mdi-account-outline text-blue-500 text-lg mr-3"></i>
                    <span>Profil Saya</span>
                </a>
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('pengaturans.index') }}"
                        class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="mdi mdi-cog-outline text-blue-500 text-lg mr-3"></i>
                        <span>Pengaturan Sistem</span>
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="block w-full">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-4 py-3 text-red-600 hover:bg-red-50 transition-colors duration-200 text-left cursor-pointer border-t border-gray-50">
                        <i class="mdi mdi-logout text-red-500 text-lg mr-3"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
