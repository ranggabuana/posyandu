<div id="sidebar"
    class="sidebar w-64 bg-white shadow-lg h-screen fixed z-10 transform transition-all duration-300 ease-in-out lg:translate-x-0 -translate-x-full lg:translate-x-0">
    <div class="p-5 border-b border-gray-200 flex items-center sidebar-header">
        <i class="mdi mdi-hospital-building text-blue-500 mr-2 text-xl"></i>
        <h1 class="text-xl font-bold text-gray-800 font-sans">SIP Posyandu</h1>
    </div>
    <div class="px-6 py-4 border-b border-gray-200 sidebar-user-info">
        <div class="flex items-center justify-start">
            <div class="flex-shrink-0">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white text-left">
                    <span class="font-semibold">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                </div>
            </div>
            <div class="ml-3 text-left">
                <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'Admin User' }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->roles->pluck('name')->implode(', ') ?? 'Admin' }}</p>
            </div>
        </div>
    </div>
    <nav class="mt-5 pb-20 text-left">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center justify-start px-6 py-3 transition-colors group {{ request()->routeIs('dashboard') ? 'active-menu font-bold' : 'text-gray-700' }} menu-item text-left">
            <i
                class="mdi mdi-home mr-3 transition-colors text-lg {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-blue-500' }}"></i>
            <span class="font-medium">Beranda</span>
        </a>

        <!-- Data Kependudukan -->
        @php $kependudukanActive = request()->routeIs('penduduks.*', 'ibus.*'); @endphp
        <div class="relative text-left menu-group {{ $kependudukanActive ? 'open' : '' }}">
            <button onclick="this.parentElement.classList.toggle('open')"
                class="w-full flex items-center justify-between px-6 py-3 transition-colors group menu-item text-left cursor-pointer {{ $kependudukanActive ? 'bg-blue-50/30 text-blue-600 font-semibold' : 'text-gray-700' }}">
                <div class="flex items-center justify-start">
                    <i
                        class="mdi mdi-account-group mr-3 text-lg transition-colors {{ $kependudukanActive ? 'text-blue-500' : 'text-gray-500 group-hover:text-blue-500' }}"></i>
                    <span class="font-medium">Kependudukan</span>
                </div>
                <i
                    class="mdi mdi-chevron-down ml-2 transition-transform duration-300 text-lg {{ $kependudukanActive ? 'text-blue-500' : 'text-gray-500' }}"></i>
            </button>
            <div class="submenu mt-1">
                <a href="{{ route('penduduks.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('penduduks.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('penduduks.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Semua Penduduk</span>
                </a>
                <a href="{{ route('ibus.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('ibus.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('ibus.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Data Ibu</span>
                </a>
            </div>
        </div>

        <!-- Data Kesehatan -->
        @php $kesehatanActive = request()->routeIs('ibu-hamils.*', 'bayi-balitas.*', 'balitas.*', 'lansias.*', 'wuses.*', 'puses.*'); @endphp
        <div class="relative text-left menu-group {{ $kesehatanActive ? 'open' : '' }}">
            <button onclick="this.parentElement.classList.toggle('open')"
                class="w-full flex items-center justify-between px-6 py-3 transition-colors group menu-item text-left cursor-pointer {{ $kesehatanActive ? 'bg-blue-50/30 text-blue-600 font-semibold' : 'text-gray-700' }}">
                <div class="flex items-center justify-start">
                    <i
                        class="mdi mdi-medical-bag mr-3 text-lg transition-colors {{ $kesehatanActive ? 'text-blue-500' : 'text-gray-500 group-hover:text-blue-500' }}"></i>
                    <span class="font-medium">Data Kesehatan</span>
                </div>
                <i
                    class="mdi mdi-chevron-down ml-2 transition-transform duration-300 text-lg {{ $kesehatanActive ? 'text-blue-500' : 'text-gray-500' }}"></i>
            </button>
            <div class="submenu mt-1">
                <a href="{{ route('ibu-hamils.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('ibu-hamils.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('ibu-hamils.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Ibu Hamil</span>
                </a>
                <a href="{{ route('bayi-balitas.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('bayi-balitas.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('bayi-balitas.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Bayi</span>
                </a>
                <a href="{{ route('balitas.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('balitas.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('balitas.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Balita</span>
                </a>
                <a href="{{ route('lansias.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('lansias.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('lansias.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Lansia</span>
                </a>
                <a href="{{ route('wuses.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('wuses.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('wuses.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>WUS</span>
                </a>
                <a href="{{ route('puses.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('puses.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('puses.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>PUS</span>
                </a>
            </div>
        </div>

        <!-- Konten Publik -->
        @php $kontenActive = request()->routeIs('beritas.*', 'galeries.*', 'jadwals.*', 'tims.*'); @endphp
        <div class="relative text-left menu-group {{ $kontenActive ? 'open' : '' }}">
            <button onclick="this.parentElement.classList.toggle('open')"
                class="w-full flex items-center justify-between px-6 py-3 transition-colors group menu-item text-left cursor-pointer {{ $kontenActive ? 'bg-blue-50/30 text-blue-600 font-semibold' : 'text-gray-700' }}">
                <div class="flex items-center justify-start">
                    <i
                        class="mdi mdi-newspaper-variant mr-3 text-lg transition-colors {{ $kontenActive ? 'text-blue-500' : 'text-gray-500 group-hover:text-blue-500' }}"></i>
                    <span class="font-medium">Konten</span>
                </div>
                <i
                    class="mdi mdi-chevron-down ml-2 transition-transform duration-300 text-lg {{ $kontenActive ? 'text-blue-500' : 'text-gray-500' }}"></i>
            </button>
            <div class="submenu mt-1">
                <a href="{{ route('beritas.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('beritas.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('beritas.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Berita</span>
                </a>
                <a href="{{ route('galeries.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('galeries.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('galeries.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Galeri</span>
                </a>
                <a href="{{ route('jadwals.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('jadwals.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('jadwals.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Jadwal Pelayanan</span>
                </a>
                <a href="{{ route('tims.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('tims.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('tims.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Tim Kami</span>
                </a>
            </div>
        </div>

        <!-- Interaksi -->
        @php $interaksiActive = request()->routeIs('buku-tamus.*', 'laporan-masyarakats.*'); @endphp
        <div class="relative text-left menu-group {{ $interaksiActive ? 'open' : '' }}">
            <button onclick="this.parentElement.classList.toggle('open')"
                class="w-full flex items-center justify-between px-6 py-3 transition-colors group menu-item text-left cursor-pointer {{ $interaksiActive ? 'bg-blue-50/30 text-blue-600 font-semibold' : 'text-gray-700' }}">
                <div class="flex items-center justify-start">
                    <i
                        class="mdi mdi-message-processing mr-3 text-lg transition-colors {{ $interaksiActive ? 'text-blue-500' : 'text-gray-500 group-hover:text-blue-500' }}"></i>
                    <span class="font-medium">Interaksi</span>
                </div>
                <i
                    class="mdi mdi-chevron-down ml-2 transition-transform duration-300 text-lg {{ $interaksiActive ? 'text-blue-500' : 'text-gray-500' }}"></i>
            </button>
            <div class="submenu mt-1">
                <a href="{{ route('buku-tamus.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('buku-tamus.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('buku-tamus.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Buku Tamu</span>
                </a>
                <a href="{{ route('laporan-masyarakats.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('laporan-masyarakats.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('laporan-masyarakats.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Laporan Masyarakat</span>
                </a>
            </div>
        </div>

        <!-- Pengaturan -->
        @php $pengaturanActive = request()->routeIs('posyandus.*', 'kaders.*', 'pengaturans.*', 'admin.users.*'); @endphp
        <div class="relative text-left menu-group {{ $pengaturanActive ? 'open' : '' }}">
            <button onclick="this.parentElement.classList.toggle('open')"
                class="w-full flex items-center justify-between px-6 py-3 transition-colors group menu-item text-left cursor-pointer {{ $pengaturanActive ? 'bg-blue-50/30 text-blue-600 font-semibold' : 'text-gray-700' }}">
                <div class="flex items-center justify-start">
                    <i
                        class="mdi mdi-cog mr-3 text-lg transition-colors {{ $pengaturanActive ? 'text-blue-500' : 'text-gray-500 group-hover:text-blue-500' }}"></i>
                    <span class="font-medium">Pengaturan</span>
                </div>
                <i
                    class="mdi mdi-chevron-down ml-2 transition-transform duration-300 text-lg {{ $pengaturanActive ? 'text-blue-500' : 'text-gray-500' }}"></i>
            </button>
            <div class="submenu mt-1">
                <a href="{{ route('admin.users.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('admin.users.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('admin.users.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Data User Admin</span>
                </a>
                <a href="{{ route('posyandus.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('posyandus.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('posyandus.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Data Posyandu</span>
                </a>
                <a href="{{ route('kaders.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('kaders.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('kaders.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Data Kader</span>
                </a>
                <a href="{{ route('pengaturans.index') }}"
                    class="block pl-14 py-2 text-sm transition-colors flex items-center submenu-item text-left {{ request()->routeIs('pengaturans.*') ? 'text-blue-600 font-semibold bg-blue-50/50 border-r-4 border-blue-500' : 'text-gray-600' }}">
                    <i
                        class="mdi mdi-circle text-[8px] mr-2 {{ request()->routeIs('pengaturans.*') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                    <span>Pengaturan Sistem</span>
                </a>
            </div>
        </div>

        <hr class="my-3 border-gray-200">

        <!-- Sync API Asmara Button -->
        <a href="{{ route('sync-asmara') }}" onclick="confirmSync(event, this.href)"
            class="mt-2 w-full flex items-center justify-start px-6 py-3 bg-green-50 text-green-600 hover:bg-green-100 transition-colors duration-200 border-l-4 border-green-500 hover:border-green-600 cursor-pointer text-left">
            <i class="mdi mdi-cloud-sync text-lg mr-3 text-left"></i>
            <span class="font-medium text-left">Sinkronisasi Penduduk SID</span>
        </a>

        <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST" class="w-full mt-auto text-left">
            @csrf
            <button type="submit"
                class="mt-2 w-full flex items-center justify-start px-6 py-3 bg-red-50 text-red-600 hover:bg-red-100 transition-colors duration-200 border-l-4 border-red-500 hover:border-red-600 cursor-pointer text-left">
                <i class="mdi mdi-logout text-lg mr-3 text-left"></i>
                <span class="font-medium text-left">Logout</span>
            </button>
        </form>
    </nav>
</div>

@push('scripts')
<script>
    function confirmSync(event, url) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Sinkronisasi',
            text: 'Sinkronisasi penduduk dari OpenSID akan dilakukan. Proses ini memakan waktu sekitar 1 hingga 3 menit tergantung jumlah data.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Sinkronkan!',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                Swal.fire({
                    title: 'Sedang Menyinkronkan...',
                    text: 'Mohon jangan tutup halaman ini.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                        window.location.href = url;
                    }
                });
            }
        });
    }
</script>
@endpush
