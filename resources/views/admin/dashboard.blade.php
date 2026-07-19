<x-layout title="Dashboard SIP Posyandu">
    <x-page-header 
        title="Beranda SIP Posyandu"
        subtitle="Panel Kontrol & Analisis Rekam Medis Pelayanan Kesehatan Terpadu Desa Banjar"
        icon="mdi-home"
        :breadcrumbs="[]"
    >
        <a href="{{ route('sync-asmara') }}" onclick="confirmSync(event, this.href)" 
            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 rounded-xl text-xs font-bold transition shadow-2xs">
            <i class="mdi mdi-cloud-sync text-sm"></i>
            <span>Sinkronkan Data OpenSID</span>
        </a>
    </x-page-header>

    <!-- Section 1: Ringkasan Populasi Lintas Siklus Hidup -->
    <div class="mb-8">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
            <i class="mdi mdi-account-group-outline text-base text-blue-600"></i>
            Populasi Lintas Siklus Hidup (Life Course Approach)
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
            <!-- Penduduk -->
            <a href="{{ route('penduduks.index') }}" class="bg-white p-3.5 rounded-2xl shadow-2xs border border-gray-100 hover:border-blue-300 hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-xl group-hover:scale-110 transition">
                        <i class="mdi mdi-account-group text-base"></i>
                    </div>
                    <span class="text-[10px] text-gray-400 font-mono bg-gray-50 px-1.5 py-0.5 rounded-full border border-gray-100">Semua Warga</span>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mt-2">{{ number_format($totalPenduduk) }}</h4>
                <p class="text-[11px] text-gray-500 font-medium truncate">Total Penduduk Desa</p>
            </a>

            <!-- Ibu Hamil -->
            <a href="{{ route('ibu-hamils.index') }}" class="bg-white p-3.5 rounded-2xl shadow-2xs border border-gray-100 hover:border-pink-300 hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div class="p-2 bg-pink-50 text-pink-600 rounded-xl group-hover:scale-110 transition">
                        <i class="mdi mdi-human-pregnant text-base"></i>
                    </div>
                    <span class="text-[10px] text-pink-600 font-bold bg-pink-50 px-1.5 py-0.5 rounded-full border border-pink-100">Bumil</span>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mt-2">{{ number_format($totalIbuHamil) }}</h4>
                <p class="text-[11px] text-gray-500 font-medium truncate">Ibu Hamil Terdata</p>
            </a>

            <!-- Bayi -->
            <a href="{{ route('bayi-balitas.index') }}" class="bg-white p-3.5 rounded-2xl shadow-2xs border border-gray-100 hover:border-purple-300 hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div class="p-2 bg-purple-50 text-purple-600 rounded-xl group-hover:scale-110 transition">
                        <i class="mdi mdi-baby-face-outline text-base"></i>
                    </div>
                    <span class="text-[10px] text-purple-600 font-bold bg-purple-50 px-1.5 py-0.5 rounded-full border border-purple-100">0-12 Bln</span>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mt-2">{{ number_format($totalBayi) }}</h4>
                <p class="text-[11px] text-gray-500 font-medium truncate">Bayi Usia 0-12 Bulan</p>
            </a>

            <!-- Balita -->
            <a href="{{ route('balitas.index') }}" class="bg-white p-3.5 rounded-2xl shadow-2xs border border-gray-100 hover:border-indigo-300 hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl group-hover:scale-110 transition">
                        <i class="mdi mdi-baby text-base"></i>
                    </div>
                    <span class="text-[10px] text-indigo-600 font-bold bg-indigo-50 px-1.5 py-0.5 rounded-full border border-indigo-100">13-60 Bln</span>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mt-2">{{ number_format($totalBalita) }}</h4>
                <p class="text-[11px] text-gray-500 font-medium truncate">Balita Usia 13-60 Bulan</p>
            </a>

            <!-- Remaja -->
            <a href="{{ route('remajas.index') }}" class="bg-white p-3.5 rounded-2xl shadow-2xs border border-gray-100 hover:border-cyan-300 hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div class="p-2 bg-cyan-50 text-cyan-600 rounded-xl group-hover:scale-110 transition">
                        <i class="mdi mdi-account-school text-base"></i>
                    </div>
                    <span class="text-[10px] text-cyan-600 font-bold bg-cyan-50 px-1.5 py-0.5 rounded-full border border-cyan-100">Remaja PKPR</span>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mt-2">{{ number_format($totalRemaja) }}</h4>
                <p class="text-[11px] text-gray-500 font-medium truncate">Remaja (10-18 Tahun)</p>
            </a>

            <!-- Lansia -->
            <a href="{{ route('lansias.index') }}" class="bg-white p-3.5 rounded-2xl shadow-2xs border border-gray-100 hover:border-emerald-300 hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl group-hover:scale-110 transition">
                        <i class="mdi mdi-account-heart text-base"></i>
                    </div>
                    <span class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-1.5 py-0.5 rounded-full border border-emerald-100">Lansia PTM</span>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mt-2">{{ number_format($totalLansia) }}</h4>
                <p class="text-[11px] text-gray-500 font-medium truncate">Lanjut Usia (>=60 Thn)</p>
            </a>

            <!-- WUS -->
            <a href="{{ route('wuses.index') }}" class="bg-white p-3.5 rounded-2xl shadow-2xs border border-gray-100 hover:border-rose-300 hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div class="p-2 bg-rose-50 text-rose-600 rounded-xl group-hover:scale-110 transition">
                        <i class="mdi mdi-human-female text-base"></i>
                    </div>
                    <span class="text-[10px] text-rose-600 font-bold bg-rose-50 px-1.5 py-0.5 rounded-full border border-rose-100">WUS</span>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mt-2">{{ number_format($totalWus) }}</h4>
                <p class="text-[11px] text-gray-500 font-medium truncate">Wanita Subur (15-49 Thn)</p>
            </a>

            <!-- PUS -->
            <a href="{{ route('puses.index') }}" class="bg-white p-3.5 rounded-2xl shadow-2xs border border-gray-100 hover:border-amber-300 hover:shadow-md transition group">
                <div class="flex items-center justify-between">
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-xl group-hover:scale-110 transition">
                        <i class="mdi mdi-heart text-base"></i>
                    </div>
                    <span class="text-[10px] text-amber-600 font-bold bg-amber-50 px-1.5 py-0.5 rounded-full border border-amber-100">PUS</span>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mt-2">{{ number_format($totalPus) }}</h4>
                <p class="text-[11px] text-gray-500 font-medium truncate">Pasangan Usia Subur</p>
            </a>
        </div>
    </div>

    <!-- Section 2: Ringkasan Hasil Skrining Kesehatan Masing-Masing Kelompok -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Deteksi Stunting & Gizi Balita (WHO Standard) -->
        <div class="bg-gradient-to-br from-white to-blue-50/40 p-6 rounded-3xl shadow-sm border border-blue-100">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-bold shadow-xs">
                        <i class="mdi mdi-chart-line text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Status Stunting & Gizi Balita</h4>
                        <p class="text-[11px] text-gray-500">Standar WHO & Permenkes RI</p>
                    </div>
                </div>
                <a href="{{ route('balitas.index') }}" class="text-blue-600 hover:text-blue-800 text-xs font-bold">Detail &rarr;</a>
            </div>

            <div class="space-y-2.5 pt-2">
                <div onclick="openPersonListModal('Daftar Balita Stunting / Pendek (TB/U)', {{ json_encode($listStunting) }})"
                    class="flex items-center justify-between p-3 bg-white rounded-2xl border border-red-100 shadow-2xs cursor-pointer hover:bg-red-50/50 hover:border-red-300 transition-all group">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500 group-hover:scale-125 transition"></div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-red-700">Stunting / Pendek (TB/U)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-xs text-red-600 px-2 py-0.5 bg-red-50 rounded-full border border-red-200">
                            {{ $stuntingCount }} Anak
                        </span>
                        <i class="mdi mdi-chevron-right text-gray-400 group-hover:text-red-600 group-hover:translate-x-0.5 transition"></i>
                    </div>
                </div>

                <div onclick="openPersonListModal('Daftar Balita Gizi Kurang (BB/U)', {{ json_encode($listGiziKurang) }})"
                    class="flex items-center justify-between p-3 bg-white rounded-2xl border border-amber-100 shadow-2xs cursor-pointer hover:bg-amber-50/50 hover:border-amber-300 transition-all group">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-amber-500 group-hover:scale-125 transition"></div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-amber-800">Gizi Kurang (BB/U)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-xs text-amber-700 px-2 py-0.5 bg-amber-50 rounded-full border border-amber-200">
                            {{ $giziKurangCount }} Anak
                        </span>
                        <i class="mdi mdi-chevron-right text-gray-400 group-hover:text-amber-600 group-hover:translate-x-0.5 transition"></i>
                    </div>
                </div>

                <div onclick="openPersonListModal('Daftar Balita Gizi Baik & Normal', {{ json_encode($listGiziNormal) }})"
                    class="flex items-center justify-between p-3 bg-white rounded-2xl border border-emerald-100 shadow-2xs cursor-pointer hover:bg-emerald-50/50 hover:border-emerald-300 transition-all group">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-emerald-500 group-hover:scale-125 transition"></div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-emerald-800">Gizi Baik & Normal</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-xs text-emerald-700 px-2 py-0.5 bg-emerald-50 rounded-full border border-emerald-200">
                            {{ $giziNormalCount }} Anak
                        </span>
                        <i class="mdi mdi-chevron-right text-gray-400 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Skrining KEK & Anemia Remaja (PKPR Kemenkes) -->
        <div class="bg-gradient-to-br from-white to-cyan-50/40 p-6 rounded-3xl shadow-sm border border-cyan-100">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-2xl bg-cyan-600 text-white flex items-center justify-center font-bold shadow-xs">
                        <i class="mdi mdi-account-school text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Skrining Kesehatan Remaja</h4>
                        <p class="text-[11px] text-gray-500">Standar PKPR Kemenkes RI</p>
                    </div>
                </div>
                <a href="{{ route('remajas.index') }}" class="text-cyan-600 hover:text-cyan-800 text-xs font-bold">Detail &rarr;</a>
            </div>

            <div class="space-y-2.5 pt-2">
                <div onclick="openPersonListModal('Daftar Remaja Resiko KEK (LiLA < 23.5 cm)', {{ json_encode($listRemajaKek) }})"
                    class="flex items-center justify-between p-3 bg-white rounded-2xl border border-rose-100 shadow-2xs cursor-pointer hover:bg-rose-50/50 hover:border-rose-300 transition-all group">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-rose-500 group-hover:scale-125 transition"></div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-rose-700">Resiko KEK (LiLA < 23.5 cm)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-xs text-rose-600 px-2 py-0.5 bg-rose-50 rounded-full border border-rose-200">
                            {{ $remajaKekCount }} Orang
                        </span>
                        <i class="mdi mdi-chevron-right text-gray-400 group-hover:text-rose-600 group-hover:translate-x-0.5 transition"></i>
                    </div>
                </div>

                <div onclick="openPersonListModal('Daftar Remaja Terindikasi Anemia (HB)', {{ json_encode($listRemajaAnemia) }})"
                    class="flex items-center justify-between p-3 bg-white rounded-2xl border border-red-100 shadow-2xs cursor-pointer hover:bg-red-50/50 hover:border-red-300 transition-all group">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500 group-hover:scale-125 transition"></div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-red-700">Terindikasi Anemia (HB)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-xs text-red-600 px-2 py-0.5 bg-red-50 rounded-full border border-red-200">
                            {{ $remajaAnemiaCount }} Orang
                        </span>
                        <i class="mdi mdi-chevron-right text-gray-400 group-hover:text-red-600 group-hover:translate-x-0.5 transition"></i>
                    </div>
                </div>

                <div onclick="openPersonListModal('Daftar Remaja Penerima Tablet Tambah Darah (TTD)', {{ json_encode($listRemajaTtd) }})"
                    class="flex items-center justify-between p-3 bg-white rounded-2xl border border-blue-100 shadow-2xs cursor-pointer hover:bg-blue-50/50 hover:border-blue-300 transition-all group">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-blue-500 group-hover:scale-125 transition"></div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-blue-700">Penerima Tablet TTD</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-xs text-blue-700 px-2 py-0.5 bg-blue-50 rounded-full border border-blue-200">
                            {{ $remajaTtdCount }} Remaja
                        </span>
                        <i class="mdi mdi-chevron-right text-gray-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Skrining Penyakit Tidak Menular Lansia (PTM) -->
        <div class="bg-gradient-to-br from-white to-emerald-50/40 p-6 rounded-3xl shadow-sm border border-emerald-100">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-bold shadow-xs">
                        <i class="mdi mdi-heart-pulse text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Skrining Kesehatan Lansia</h4>
                        <p class="text-[11px] text-gray-500">Standar PTM Kemenkes RI</p>
                    </div>
                </div>
                <a href="{{ route('lansias.index') }}" class="text-emerald-600 hover:text-emerald-800 text-xs font-bold">Detail &rarr;</a>
            </div>

            <div class="space-y-2.5 pt-2">
                <div onclick="openPersonListModal('Daftar Lansia Hipertensi (Tensi >= 140)', {{ json_encode($listLansiaHipertensi) }})"
                    class="flex items-center justify-between p-3 bg-white rounded-2xl border border-red-100 shadow-2xs cursor-pointer hover:bg-red-50/50 hover:border-red-300 transition-all group">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-500 group-hover:scale-125 transition"></div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-red-700">Hipertensi (Tensi >= 140)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-xs text-red-600 px-2 py-0.5 bg-red-50 rounded-full border border-red-200">
                            {{ $lansiaHipertensiCount }} Lansia
                        </span>
                        <i class="mdi mdi-chevron-right text-gray-400 group-hover:text-red-600 group-hover:translate-x-0.5 transition"></i>
                    </div>
                </div>

                <div onclick="openPersonListModal('Daftar Lansia Resiko Diabetes / Gula Darah Tinggi', {{ json_encode($listLansiaDiabetes) }})"
                    class="flex items-center justify-between p-3 bg-white rounded-2xl border border-amber-100 shadow-2xs cursor-pointer hover:bg-amber-50/50 hover:border-amber-300 transition-all group">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-amber-500 group-hover:scale-125 transition"></div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-amber-800">Resiko Diabetes / Gula</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-xs text-amber-700 px-2 py-0.5 bg-amber-50 rounded-full border border-amber-200">
                            {{ $lansiaDiabetesCount }} Lansia
                        </span>
                        <i class="mdi mdi-chevron-right text-gray-400 group-hover:text-amber-600 group-hover:translate-x-0.5 transition"></i>
                    </div>
                </div>

                <div onclick="openPersonListModal('Daftar Lansia Kolesterol Tinggi', {{ json_encode($listLansiaKolesterol) }})"
                    class="flex items-center justify-between p-3 bg-white rounded-2xl border border-purple-100 shadow-2xs cursor-pointer hover:bg-purple-50/50 hover:border-purple-300 transition-all group">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-purple-500 group-hover:scale-125 transition"></div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:text-purple-800">Kolesterol Tinggi</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-xs text-purple-700 px-2 py-0.5 bg-purple-50 rounded-full border border-purple-200">
                            {{ $lansiaKolesterolCount }} Lansia
                        </span>
                        <i class="mdi mdi-chevron-right text-gray-400 group-hover:text-purple-600 group-hover:translate-x-0.5 transition"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Grafik Analisis Real-Time -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Chart 1: Total Rekam Pemeriksaan Lintas Usia -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="mdi mdi-chart-bar text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Volume Rekam Pemeriksaan Kesehatan</h3>
                        <p class="text-xs text-gray-500">Total rekam medis terdaftar per kategori sasaran</p>
                    </div>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="examVolumeChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Faktor Resiko Ibu Hamil -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-pink-50 flex items-center justify-center text-pink-600">
                        <i class="mdi mdi-chart-pie text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Faktor Risiko Ibu Hamil</h3>
                        <p class="text-xs text-gray-500">Deteksi dini kehamilan</p>
                    </div>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="riskFactorChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Section 4: Akses Cepat & Operasional Posyandu -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        <!-- Left: Quick Actions -->
        <div class="lg:col-span-8 bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="mdi mdi-lightning-bolt text-amber-500 text-lg"></i>
                Akses Cepat Pelayanan & Rekam Medis
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                <a href="{{ route('bayi-balitas.index') }}" class="p-3.5 bg-blue-50/60 hover:bg-blue-50 text-blue-700 rounded-2xl border border-blue-100 text-center transition flex flex-col items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold group-hover:scale-110 transition shadow-2xs">
                        <i class="mdi mdi-scale-bathroom text-lg"></i>
                    </div>
                    <span class="text-xs font-bold leading-snug">Pemeriksaan Balita</span>
                </a>

                <a href="{{ route('ibu-hamils.index') }}" class="p-3.5 bg-pink-50/60 hover:bg-pink-50 text-pink-700 rounded-2xl border border-pink-100 text-center transition flex flex-col items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-pink-600 text-white flex items-center justify-center font-bold group-hover:scale-110 transition shadow-2xs">
                        <i class="mdi mdi-human-pregnant text-lg"></i>
                    </div>
                    <span class="text-xs font-bold leading-snug">Pemeriksaan Bumil</span>
                </a>

                <a href="{{ route('remajas.index') }}" class="p-3.5 bg-cyan-50/60 hover:bg-cyan-50 text-cyan-700 rounded-2xl border border-cyan-100 text-center transition flex flex-col items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-cyan-600 text-white flex items-center justify-center font-bold group-hover:scale-110 transition shadow-2xs">
                        <i class="mdi mdi-heart-pulse text-lg"></i>
                    </div>
                    <span class="text-xs font-bold leading-snug">Skrining Remaja</span>
                </a>

                <a href="{{ route('lansias.index') }}" class="p-3.5 bg-emerald-50/60 hover:bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 text-center transition flex flex-col items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold group-hover:scale-110 transition shadow-2xs">
                        <i class="mdi mdi-account-heart text-lg"></i>
                    </div>
                    <span class="text-xs font-bold leading-snug">Skrining Lansia</span>
                </a>

                <a href="{{ route('laporan-masyarakats.index') }}" class="p-3.5 bg-purple-50/60 hover:bg-purple-50 text-purple-700 rounded-2xl border border-purple-100 text-center transition flex flex-col items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold group-hover:scale-110 transition shadow-2xs">
                        <i class="mdi mdi-message-alert text-lg"></i>
                    </div>
                    <span class="text-xs font-bold leading-snug">Laporan Warga</span>
                </a>

                <a href="{{ route('sync-asmara') }}" onclick="confirmSync(event, this.href)" class="p-3.5 bg-emerald-50/60 hover:bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 text-center transition flex flex-col items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold group-hover:scale-110 transition shadow-2xs">
                        <i class="mdi mdi-cloud-sync text-lg"></i>
                    </div>
                    <span class="text-xs font-bold leading-snug">Sync OpenSID</span>
                </a>
            </div>
        </div>

        <!-- Right: Status Operasional Posyandu & Interaksi -->
        <div class="lg:col-span-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="mdi mdi-hospital-building text-blue-600 text-lg"></i>
                    Operasional & Interaksi Publik
                </h3>
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between items-center p-2.5 bg-gray-50 rounded-xl">
                        <span class="text-gray-600">Unit Posyandu Desa:</span>
                        <span class="font-bold text-gray-900">{{ number_format($totalPosyandu) }} Unit RW</span>
                    </div>
                    <div class="flex justify-between items-center p-2.5 bg-gray-50 rounded-xl">
                        <span class="text-gray-600">Kader Kesehatan Aktif:</span>
                        <span class="font-bold text-gray-900">{{ number_format($totalKader) }} Orang</span>
                    </div>
                    <div class="flex justify-between items-center p-2.5 bg-gray-50 rounded-xl">
                        <span class="text-gray-600">Buku Tamu Kunjungan:</span>
                        <span class="font-bold text-gray-900">{{ number_format($totalBukuTamu) }} Tamu</span>
                    </div>
                    <div class="flex justify-between items-center p-2.5 bg-gray-50 rounded-xl">
                        <span class="text-gray-600">Aduan & Laporan Warga:</span>
                        <div class="flex items-center gap-1.5">
                            <span class="font-bold text-gray-900">{{ number_format($totalLaporan) }}</span>
                            @if($laporanPending > 0)
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-800 rounded-full font-bold text-[10px]">
                                    {{ $laporanPending }} Perlu Proses
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-100 text-[11px] text-gray-400 flex items-center justify-between">
                <span>Posyandu Melati Sehat Desa Banjar</span>
                <span class="font-mono text-emerald-600 font-bold">Online</span>
            </div>
        </div>
    </div>

    <!-- Section 5: Activity Feed - Rekam Pemeriksaan Terbaru -->
    <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="mdi mdi-clock-outline text-blue-600 text-lg"></i>
                    Aktivitas Pemeriksaan Kesehatan Terbaru
                </h3>
                <p class="text-xs text-gray-500">Log entry hasil timbangan & skrining kesehatan terkini</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Balita Feed -->
            <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                <h4 class="text-xs font-bold text-gray-700 mb-3 flex items-center justify-between">
                    <span class="flex items-center gap-1.5"><i class="mdi mdi-baby text-blue-600"></i> Balita Terbaru</span>
                    <a href="{{ route('balitas.index') }}" class="text-[11px] text-blue-600 hover:underline">Semua</a>
                </h4>
                <div class="space-y-2 text-xs">
                    @forelse($recentBalitaExams as $item)
                        <div class="p-2.5 bg-white rounded-xl border border-gray-100 shadow-2xs">
                            <div class="flex justify-between font-bold text-gray-900">
                                <span>{{ $item->bayiBalita->penduduk->nama ?? 'Balita' }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">{{ \Carbon\Carbon::parse($item->tanggal_pemeriksaan)->format('d/m/Y') }}</span>
                            </div>
                            <div class="text-[11px] text-gray-500 mt-0.5">
                                BB: {{ $item->berat_badan }}kg | TB: {{ $item->tinggi_badan }}cm ({{ $item->umur_bulan }} Bln)
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic py-2">Belum ada riwayat terbaru.</p>
                    @endforelse
                </div>
            </div>

            <!-- Remaja Feed -->
            <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                <h4 class="text-xs font-bold text-gray-700 mb-3 flex items-center justify-between">
                    <span class="flex items-center gap-1.5"><i class="mdi mdi-account-school text-cyan-600"></i> Remaja Terbaru</span>
                    <a href="{{ route('remajas.index') }}" class="text-[11px] text-cyan-600 hover:underline">Semua</a>
                </h4>
                <div class="space-y-2 text-xs">
                    @forelse($recentRemajaExams as $item)
                        <div class="p-2.5 bg-white rounded-xl border border-gray-100 shadow-2xs">
                            <div class="flex justify-between font-bold text-gray-900">
                                <span>{{ $item->remaja->penduduk->nama ?? 'Remaja' }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">{{ \Carbon\Carbon::parse($item->tanggal_pemeriksaan)->format('d/m/Y') }}</span>
                            </div>
                            <div class="text-[11px] text-gray-500 mt-0.5">
                                LiLA: {{ $item->lila ?? '-' }}cm | HB: {{ $item->hemoglobin ?? '-' }}g/dL | TTD: {{ $item->pemberian_ttd }}
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic py-2">Belum ada riwayat terbaru.</p>
                    @endforelse
                </div>
            </div>

            <!-- Lansia Feed -->
            <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                <h4 class="text-xs font-bold text-gray-700 mb-3 flex items-center justify-between">
                    <span class="flex items-center gap-1.5"><i class="mdi mdi-account-heart text-emerald-600"></i> Lansia Terbaru</span>
                    <a href="{{ route('lansias.index') }}" class="text-[11px] text-emerald-600 hover:underline">Semua</a>
                </h4>
                <div class="space-y-2 text-xs">
                    @forelse($recentLansiaExams as $item)
                        <div class="p-2.5 bg-white rounded-xl border border-gray-100 shadow-2xs">
                            <div class="flex justify-between font-bold text-gray-900">
                                <span>{{ $item->lansia->penduduk->nama ?? 'Lansia' }}</span>
                                <span class="text-[10px] text-gray-400 font-mono">{{ \Carbon\Carbon::parse($item->tanggal_pemeriksaan)->format('d/m/Y') }}</span>
                            </div>
                            <div class="text-[11px] text-gray-500 mt-0.5">
                                Tensi: {{ $item->tensi_sistolik ? $item->tensi_sistolik.'/'.$item->tensi_diastolik : '-' }} | Gula: {{ $item->gula_darah ?? '-' }}mg/dL
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic py-2">Belum ada riwayat terbaru.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail List Individu / Pasien -->
    <div id="person-detail-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-3xl w-full p-6 md:p-8 shadow-2xl relative max-h-[85vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                        <i class="mdi mdi-account-search text-xl"></i>
                    </div>
                    <div>
                        <h3 id="person-modal-title" class="text-base font-bold text-gray-900">Daftar Individu</h3>
                        <p class="text-xs text-gray-500">Daftar warga berdasarkan kriteria skrining kesehatan</p>
                    </div>
                </div>
                <button onclick="closePersonListModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="mdi mdi-close text-xl"></i>
                </button>
            </div>

            <!-- Filter Search Bar in Modal -->
            <div class="mt-4 mb-3 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="mdi mdi-magnify text-lg"></i>
                </span>
                <input type="text" id="person-modal-search" placeholder="Cari nama, NIK, atau wilayah..." onkeyup="filterPersonModalList()"
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:bg-white focus:border-blue-500 outline-none">
            </div>

            <!-- Modal Table Body -->
            <div class="overflow-y-auto flex-1 border border-gray-100 rounded-2xl">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="sticky top-0 bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="py-3 px-4 font-bold text-gray-600 uppercase">Nama & NIK</th>
                            <th class="py-3 px-4 font-bold text-gray-600 uppercase">Usia / Wilayah</th>
                            <th class="py-3 px-4 font-bold text-gray-600 uppercase">Hasil / Status Medis</th>
                            <th class="py-3 px-4 font-bold text-gray-600 uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="person-modal-tbody" class="divide-y divide-gray-100 bg-white">
                        <!-- Dynamic Rows Insertion -->
                    </tbody>
                </table>
            </div>

            <!-- Modal Footer -->
            <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center text-xs">
                <span id="person-modal-count" class="text-gray-500 font-medium">Total: 0 orang</span>
                <button onclick="closePersonListModal()" class="px-5 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let currentModalData = [];

        function openPersonListModal(title, listData) {
            document.getElementById('person-modal-title').textContent = title;
            document.getElementById('person-modal-search').value = '';
            currentModalData = listData || [];
            renderPersonModalTable(currentModalData);
            document.getElementById('person-detail-modal').classList.remove('hidden');
        }

        function closePersonListModal() {
            document.getElementById('person-detail-modal').classList.add('hidden');
        }

        function filterPersonModalList() {
            const query = document.getElementById('person-modal-search').value.toLowerCase();
            const filtered = currentModalData.filter(item => 
                (item.nama && item.nama.toLowerCase().includes(query)) ||
                (item.nik && item.nik.toLowerCase().includes(query)) ||
                (item.wilayah && item.wilayah.toLowerCase().includes(query)) ||
                (item.detail && item.detail.toLowerCase().includes(query))
            );
            renderPersonModalTable(filtered);
        }

        function renderPersonModalTable(data) {
            const tbody = document.getElementById('person-modal-tbody');
            document.getElementById('person-modal-count').textContent = `Total: ${data.length} orang`;

            if (!data || data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-400">
                            <i class="mdi mdi-account-search-outline text-4xl block mb-2"></i>
                            Tidak ada data individu yang tercatat dalam kriteria ini.
                        </td>
                    </tr>
                `;
                return;
            }

            let html = '';
            data.forEach(item => {
                html += `
                    <tr class="hover:bg-blue-50/20 transition-colors">
                        <td class="py-3 px-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">${item.nama}</div>
                            <div class="text-[10px] text-gray-400 font-mono">NIK: ${item.nik}</div>
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            <div class="font-semibold text-gray-800">${item.umur}</div>
                            <div class="text-[10px] text-gray-500">${item.wilayah}</div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-block px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-xl text-[11px] font-bold">
                                ${item.detail}
                            </span>
                            <div class="text-[10px] text-gray-400 mt-0.5">Tgl Periksa: ${item.tanggal}</div>
                        </td>
                        <td class="py-3 px-4 text-center whitespace-nowrap">
                            <a href="${item.link}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition shadow-2xs">
                                <i class="mdi mdi-heart-pulse"></i>
                                <span>Pemeriksaan</span>
                            </a>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Rekam Pemeriksaan Lintas Usia
            const ctx1 = document.getElementById('examVolumeChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: ['Ibu Hamil', 'Bayi (0-12m)', 'Balita (13-60m)', 'Remaja (PKPR)', 'Lansia (PTM)'],
                    datasets: [{
                        label: 'Total Rekam Medis Terdaftar',
                        data: [
                            {{ $ibuHamilExamCount }}, 
                            {{ $bayiExamCount }}, 
                            {{ $balitaExamCount }}, 
                            {{ $remajaExamCount }}, 
                            {{ $lansiaExamCount }}
                        ],
                        backgroundColor: [
                            'rgba(236, 72, 153, 0.85)',
                            'rgba(168, 85, 247, 0.85)',
                            'rgba(99, 102, 241, 0.85)',
                            'rgba(6, 182, 212, 0.85)',
                            'rgba(16, 185, 129, 0.85)'
                        ],
                        borderRadius: 12,
                        maxBarThickness: 45
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.04)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Chart 2: Faktor Risiko Ibu Hamil
            const ctx2 = document.getElementById('riskFactorChart').getContext('2d');
            const riskData = @json($riskFactors);
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(riskData),
                    datasets: [{
                        data: Object.values(riskData),
                        backgroundColor: [
                            '#ef4444',
                            '#f97316',
                            '#f59e0b',
                            '#ec4899',
                            '#8b5cf6',
                            '#10b981'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: { size: 10 }
                            }
                        }
                    }
                }
            });
        });

        function confirmSync(event, url) {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Sinkronisasi',
                text: 'Sinkronisasi penduduk dari OpenSID akan dilakukan. Proses ini memakan waktu sekitar 1 hingga 3 menit.',
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
</x-layout>
