<x-layout title="Dashboard">
    <x-page-header 
        title="Beranda SIP Posyandu"
        subtitle="Selamat datang kembali di Panel Admin Sistem Informasi Posyandu Desa Banjar"
        icon="mdi-home"
        :breadcrumbs="[]"
    />

    @php
        $remajaMin = \App\Models\Pengaturan::where('key', 'remaja_umur_min')->value('value') ?? 10;
        $remajaMax = \App\Models\Pengaturan::where('key', 'remaja_umur_max')->value('value') ?? 18;
        $totalRemaja = \App\Models\Penduduk::whereRaw("TIMESTAMPDIFF(YEAR, tanggallahir, CURDATE()) BETWEEN ? AND ?", [$remajaMin, $remajaMax])->count();
    @endphp

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-8">
        <!-- Card: Penduduk -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-3 hover:shadow-md transition duration-200">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                <i class="mdi mdi-account-group text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500">Penduduk</p>
                <h3 class="text-xl font-bold text-gray-900 mt-0.5">{{ \App\Models\Penduduk::count() }}</h3>
            </div>
        </div>

        <!-- Card: Ibu Hamil -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-3 hover:shadow-md transition duration-200">
            <div class="p-3 bg-pink-50 text-pink-600 rounded-xl">
                <i class="mdi mdi-face-woman text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500">Ibu Hamil</p>
                <h3 class="text-xl font-bold text-gray-900 mt-0.5">{{ \App\Models\IbuHamil::count() }}</h3>
            </div>
        </div>

        <!-- Card: Bayi -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-3 hover:shadow-md transition duration-200">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-xl">
                <i class="mdi mdi-baby-carriage text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500">Bayi</p>
                <h3 class="text-xl font-bold text-gray-900 mt-0.5">{{ $totalBayi }}</h3>
            </div>
        </div>

        <!-- Card: Balita -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-3 hover:shadow-md transition duration-200">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <i class="mdi mdi-human-child text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500">Balita</p>
                <h3 class="text-xl font-bold text-gray-900 mt-0.5">{{ $totalBalita }}</h3>
            </div>
        </div>

        <!-- Card: Remaja -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-3 hover:shadow-md transition duration-200">
            <div class="p-3 bg-cyan-50 text-cyan-600 rounded-xl">
                <i class="mdi mdi-account-school text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500">Remaja</p>
                <h3 class="text-xl font-bold text-gray-900 mt-0.5">{{ $totalRemaja }}</h3>
            </div>
        </div>

        <!-- Card: Lansia -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-200 flex items-center gap-3 hover:shadow-md transition duration-200">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <i class="mdi mdi-account-star text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-500">Lansia</p>
                <h3 class="text-xl font-bold text-gray-900 mt-0.5">{{ \App\Models\Lansia::count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Chart 1: Pemeriksaan Keaktifan -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <i class="mdi mdi-chart-bar text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Statistik Keaktifan & Pemeriksaan Bulanan</h3>
                </div>
            </div>
            <div class="h-[300px]">
                <canvas id="pemeriksaanChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Distribusi Sasaran -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-pink-50 flex items-center justify-center text-pink-600">
                        <i class="mdi mdi-chart-donut text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Distribusi Sasaran Posyandu</h3>
                </div>
            </div>
            <div class="h-[300px] relative flex items-center justify-center">
                <canvas id="distribusiSasaranChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Analytics Row 2 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Chart 3: Kurva Pertumbuhan Rata-Rata Bayi -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="mdi mdi-chart-line text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Kurva Tumbuh Kembang (Rata-rata BB Bayi 1-12 Bulan)</h3>
                </div>
            </div>
            <div class="h-[300px]">
                <canvas id="growthCurveChart"></canvas>
            </div>
        </div>

        <!-- Chart 4: Analisis Faktor Resiko Ibu Hamil -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-600">
                        <i class="mdi mdi-shield-alert text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Faktor Resiko Terdeteksi (Ibu Hamil)</h3>
                </div>
            </div>
            <div class="h-[300px] relative flex items-center justify-center">
                <canvas id="riskFactorChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Card: Laporan Terbaru -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 lg:col-span-2">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600">
                        <i class="mdi mdi-message-alert text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Laporan Masyarakat Terbaru</h3>
                </div>
                <a href="{{ route('laporan-masyarakats.index') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                    Lihat Semua
                    <i class="mdi mdi-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="space-y-4">
                @forelse(\App\Models\LaporanMasyarakat::latest()->take(5)->get() as $laporan)
                    @php
                        $avatarGradients = [
                            'bg-gradient-to-br from-teal-400 to-emerald-500 text-white',
                            'bg-gradient-to-br from-blue-400 to-indigo-500 text-white',
                            'bg-gradient-to-br from-pink-400 to-rose-500 text-white',
                            'bg-gradient-to-br from-purple-400 to-indigo-500 text-white',
                            'bg-gradient-to-br from-amber-400 to-orange-500 text-white',
                        ];
                        $avatarClass = $avatarGradients[crc32($laporan->nama_pelapor) % count($avatarGradients)];
                        
                        $kategoriConfig = [
                            'pelayanan' => ['label' => 'Pelayanan', 'color' => 'bg-indigo-50 text-indigo-700 border-indigo-100', 'icon' => 'mdi-face-agent'],
                            'infrastruktur' => ['label' => 'Infrastruktur', 'color' => 'bg-blue-50 text-blue-700 border-blue-100', 'icon' => 'mdi-office-building'],
                            'kesehatan' => ['label' => 'Kesehatan', 'color' => 'bg-teal-50 text-teal-700 border-teal-100', 'icon' => 'mdi-medical-bag'],
                            'lainnya' => ['label' => 'Lainnya', 'color' => 'bg-gray-50 text-gray-700 border-gray-100', 'icon' => 'mdi-folder-information'],
                        ];
                        $kat = $kategoriConfig[strtolower($laporan->kategori ?? 'lainnya')] ?? $kategoriConfig['lainnya'];

                        $statusConfig = [
                            'baru' => [
                                'label' => 'Baru',
                                'color' => 'bg-blue-50 text-blue-700 border-blue-100',
                                'icon' => 'mdi-clock-outline',
                                'pulse' => true
                            ],
                            'diproses' => [
                                'label' => 'Diproses',
                                'color' => 'bg-amber-50 text-amber-700 border-amber-100',
                                'icon' => 'mdi-progress-wrench',
                                'pulse' => false
                            ],
                            'selesai' => [
                                'label' => 'Selesai',
                                'color' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'icon' => 'mdi-check-circle-outline',
                                'pulse' => false
                            ],
                            'ditolak' => [
                                'label' => 'Ditolak',
                                'color' => 'bg-rose-50 text-rose-700 border-rose-100',
                                'icon' => 'mdi-close-circle-outline',
                                'pulse' => false
                            ],
                        ];
                        $stat = $statusConfig[$laporan->status] ?? $statusConfig['baru'];
                    @endphp
                    <a href="{{ route('laporan-masyarakats.edit', $laporan) }}" class="flex items-start justify-between p-4 rounded-xl border border-gray-100 bg-white hover:bg-gray-50/70 hover:shadow-md hover:border-teal-100 transition-all duration-300 group cursor-pointer">
                        <div class="flex items-start gap-4 flex-1 min-w-0">
                            <!-- Avatar -->
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm shrink-0 shadow-sm {{ $avatarClass }}">
                                {{ strtoupper(substr($laporan->nama_pelapor, 0, 1)) }}
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="text-sm font-bold text-gray-900 group-hover:text-teal-600 transition-colors">{{ $laporan->nama_pelapor }}</h4>
                                    <span class="px-2 py-0.5 text-[10px] font-semibold border rounded-md flex items-center gap-1 {{ $kat['color'] }}">
                                        <i class="mdi {{ $kat['icon'] }} text-xs"></i>
                                        {{ $kat['label'] }}
                                    </span>
                                </div>
                                
                                <p class="text-xs text-gray-600 mt-1.5 line-clamp-2 leading-relaxed">
                                    {{ $laporan->isi_laporan }}
                                </p>
                                
                                <div class="flex items-center gap-3 mt-2 text-[10px] text-gray-400 font-medium">
                                    <span class="flex items-center gap-1">
                                        <i class="mdi mdi-calendar-clock text-xs"></i>
                                        {{ $laporan->created_at->diffForHumans() }}
                                    </span>
                                    @if($laporan->no_telepon)
                                        <span class="text-gray-200">•</span>
                                        <span class="flex items-center gap-1 text-gray-500 bg-gray-50 px-2 py-0.5 rounded border border-gray-100">
                                            <i class="mdi mdi-phone text-xs text-emerald-500"></i>
                                            {{ $laporan->no_telepon }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="shrink-0 pl-4 flex flex-col items-end gap-1">
                            <span class="px-2.5 py-1 text-[11px] font-bold border rounded-full flex items-center gap-1 {{ $stat['color'] }}">
                                @if($stat['pulse'])
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                @endif
                                <i class="mdi {{ $stat['icon'] }} text-xs"></i>
                                {{ $stat['label'] }}
                            </span>
                            <span class="text-[9px] text-teal-600 group-hover:text-teal-700 transition-colors font-semibold flex items-center gap-0.5 mt-1 opacity-0 group-hover:opacity-100 duration-300">
                                Tindak Lanjut <i class="mdi mdi-chevron-right text-xs"></i>
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                            <i class="mdi mdi-message-off text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Belum ada laporan dari masyarakat</p>
                        <p class="text-xs text-gray-400 mt-1">Aduan yang dikirim oleh masyarakat akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Card: Info Posyandu -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class="mdi mdi-chart-line text-lg"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Ringkasan Posyandu</h3>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2.5 border-b border-gray-100">
                    <span class="text-sm text-gray-500 font-medium flex items-center gap-2">
                        <i class="mdi mdi-home-heart text-blue-500"></i> Total Posyandu
                    </span>
                    <span class="text-sm font-bold text-gray-900 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">{{ \App\Models\Posyandu::count() }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-gray-100">
                    <span class="text-sm text-gray-500 font-medium flex items-center gap-2">
                        <i class="mdi mdi-book-open-outline text-purple-500"></i> Total Buku Tamu
                    </span>
                    <span class="text-sm font-bold text-gray-900 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">{{ \App\Models\BukuTamu::count() }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-gray-100">
                    <span class="text-sm text-gray-500 font-medium flex items-center gap-2">
                        <i class="mdi mdi-newspaper text-indigo-500"></i> Total Berita
                    </span>
                    <span class="text-sm font-bold text-gray-900 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">{{ \App\Models\Berita::count() }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5">
                    <span class="text-sm text-gray-500 font-medium flex items-center gap-2">
                        <i class="mdi mdi-image-multiple text-pink-500"></i> Total Galeri
                    </span>
                    <span class="text-sm font-bold text-gray-900 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">{{ \App\Models\Galeri::count() }}</span>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Shared premium styles & fonts
            Chart.defaults.font.family = "'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif";
            Chart.defaults.color = "#64748b"; // Slate 500

            const tooltipStyle = {
                backgroundColor: '#0f172a', // Slate 900
                titleColor: '#ffffff',
                bodyColor: '#f1f5f9',
                titleFont: { size: 13, weight: '700' },
                bodyFont: { size: 12 },
                padding: 12,
                cornerRadius: 10,
                boxPadding: 8,
                borderColor: 'rgba(255, 255, 255, 0.1)',
                borderWidth: 1,
                shadowColor: 'rgba(0, 0, 0, 0.15)',
                shadowBlur: 10
            };

            // 1. Pemeriksaan Chart (Bar Chart)
            const pemeriksaanCtx = document.getElementById('pemeriksaanChart')?.getContext('2d');
            if (pemeriksaanCtx) {
                new Chart(pemeriksaanCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Ibu Hamil', 'Bayi', 'Balita'],
                        datasets: [
                            {
                                label: 'Total Terdaftar',
                                data: [{{ $totalIbuHamil }}, {{ $totalBayi }}, {{ $totalBalita }}],
                                backgroundColor: 'rgba(79, 70, 229, 0.85)', // indigo-600
                                borderColor: '#4f46e5',
                                borderWidth: 0,
                                borderRadius: 8,
                                borderSkipped: false,
                                maxBarThickness: 32
                            },
                            {
                                label: 'Total Pemeriksaan (Log Berat Badan)',
                                data: [{{ $ibuHamilExamCount }}, {{ $bayiExamCount }}, {{ $balitaExamCount }}],
                                backgroundColor: 'rgba(13, 148, 136, 0.85)', // teal-600
                                borderColor: '#0d9488',
                                borderWidth: 0,
                                borderRadius: 8,
                                borderSkipped: false,
                                maxBarThickness: 32
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                align: 'end',
                                labels: {
                                    boxWidth: 10,
                                    boxHeight: 10,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 20,
                                    font: {
                                        size: 12,
                                        weight: '600'
                                    }
                                }
                            },
                            tooltip: tooltipStyle
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: {
                                    color: 'rgba(226, 232, 240, 0.6)',
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    padding: 8,
                                    font: { size: 11, weight: '500' }
                                }
                            },
                            x: {
                                border: { display: false },
                                grid: { display: false },
                                ticks: {
                                    padding: 8,
                                    font: { size: 12, weight: '600' }
                                }
                            }
                        }
                    }
                });
            }

            // 2. Distribusi Sasaran Chart (Doughnut Chart with Center Text)
            const distribusiCtx = document.getElementById('distribusiSasaranChart')?.getContext('2d');
            if (distribusiCtx) {
                new Chart(distribusiCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Ibu Hamil', 'Bayi', 'Balita', 'Lansia'],
                        datasets: [{
                            data: [{{ $totalIbuHamil }}, {{ $totalBayi }}, {{ $totalBalita }}, {{ $totalLansia }}],
                            backgroundColor: [
                                '#db2777', // pink-600
                                '#8b5cf6', // purple-500
                                '#4f46e5', // indigo-600
                                '#0d9488'  // teal-600
                            ],
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '78%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 8,
                                    boxHeight: 8,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    padding: 16,
                                    font: {
                                        size: 11,
                                        weight: '600'
                                    }
                                }
                            },
                            tooltip: tooltipStyle
                        }
                    },
                    plugins: [{
                        id: 'centerText',
                        afterDraw(chart) {
                            const { ctx, chartArea: { top, bottom, left, right, width, height } } = chart;
                            ctx.save();
                            
                            // Draw number
                            ctx.font = "bold 26px 'Plus Jakarta Sans', 'Inter', sans-serif";
                            ctx.fillStyle = '#0f172a'; // slate-900
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            const total = {{ $totalIbuHamil + $totalBayi + $totalBalita + $totalLansia }};
                            ctx.fillText(total, left + width / 2, top + height / 2 - 8);
                            
                            // Draw label
                            ctx.font = "600 10px 'Plus Jakarta Sans', 'Inter', sans-serif";
                            ctx.fillStyle = '#94a3b8'; // slate-400
                            ctx.fillText('TOTAL SASARAN', left + width / 2, top + height / 2 + 16);
                            
                            ctx.restore();
                        }
                    }]
                });
            }

            // 3. Kurva Tumbuh Kembang Bayi (Line Chart with Gradient Fill)
            const growthCurveCtx = document.getElementById('growthCurveChart')?.getContext('2d');
            if (growthCurveCtx) {
                // Create elegant gradient
                const gradient = growthCurveCtx.createLinearGradient(0, 0, 0, 280);
                gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
                gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

                new Chart(growthCurveCtx, {
                    type: 'line',
                    data: {
                        labels: ['Bulan 1', 'Bulan 2', 'Bulan 3', 'Bulan 4', 'Bulan 5', 'Bulan 6', 'Bulan 7', 'Bulan 8', 'Bulan 9', 'Bulan 10', 'Bulan 11', 'Bulan 12'],
                        datasets: [{
                            label: 'Rata-rata Berat Badan (kg)',
                            data: {!! json_encode($avgBayiWeights) !!},
                            borderColor: '#4f46e5', // indigo-600
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.38,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#4f46e5',
                            pointBorderWidth: 3,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointHoverBackgroundColor: '#4f46e5',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: tooltipStyle
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: {
                                    color: 'rgba(226, 232, 240, 0.6)',
                                    borderDash: [5, 5]
                                },
                                title: {
                                    display: true,
                                    text: 'Berat Badan (kg)',
                                    font: { size: 11, weight: '600' }
                                },
                                ticks: {
                                    font: { size: 11 }
                                }
                            },
                            x: {
                                border: { display: false },
                                grid: { display: false },
                                ticks: {
                                    font: { size: 11, weight: '500' }
                                }
                            }
                        }
                    }
                });
            }

            // 4. Faktor Resiko Ibu Hamil (Horizontal Bar Chart)
            const riskFactorCtx = document.getElementById('riskFactorChart')?.getContext('2d');
            if (riskFactorCtx) {
                new Chart(riskFactorCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(array_keys($riskFactors)) !!},
                        datasets: [{
                            label: 'Jumlah Ibu Hamil',
                            data: {!! json_encode(array_values($riskFactors)) !!},
                            backgroundColor: [
                                'rgba(239, 68, 68, 0.85)',   // Red
                                'rgba(249, 115, 22, 0.85)',  // Orange
                                'rgba(245, 158, 11, 0.85)',  // Amber
                                'rgba(139, 92, 246, 0.85)',  // Violet
                                'rgba(79, 70, 229, 0.85)',   // Indigo
                                'rgba(13, 148, 136, 0.85)'   // Teal
                            ],
                            borderColor: 'transparent',
                            borderWidth: 0,
                            borderRadius: 6,
                            borderSkipped: false,
                            maxBarThickness: 16
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: tooltipStyle
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: {
                                    color: 'rgba(226, 232, 240, 0.6)',
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    stepSize: 1,
                                    precision: 0,
                                    font: { size: 11 }
                                }
                            },
                            y: {
                                border: { display: false },
                                grid: { display: false },
                                ticks: {
                                    font: { size: 11, weight: '600' }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-layout>
