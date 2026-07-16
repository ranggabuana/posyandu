<x-layout title="Detail Warga / Penduduk">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Detail Penduduk</h2>
            <p class="text-gray-500 mt-1">Informasi biodata lengkap warga bernama <strong>{{ $penduduk->nama }}</strong>.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('penduduks.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
                <i class="mdi mdi-arrow-left mr-2"></i> Kembali
            </a>
            <a href="{{ route('penduduks.edit', $penduduk) }}" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl shadow-md hover:bg-blue-700 transition-all">
                <i class="mdi mdi-pencil mr-2"></i> Edit Data
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Side: Profile Identity Card & Address -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Card 1: Biodata Umum -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-blue-500/10 rounded-lg mr-4">
                            <i class="mdi mdi-card-account-details-outline text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Biodata Umum</h3>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Nama Lengkap</span>
                        <span class="text-base font-bold text-gray-900">{{ $penduduk->nama }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">NIK (Nomor Induk Kependudukan)</span>
                        <span class="text-base font-mono font-bold text-gray-900">{{ $penduduk->nik }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Nomor KK</span>
                        <span class="text-base font-mono text-gray-900">{{ $penduduk->no_kk }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Nama Kepala Keluarga</span>
                        <span class="text-base text-gray-900">{{ $penduduk->nama_kk }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Hubungan Keluarga</span>
                        <span class="text-base capitalize text-gray-900">{{ $penduduk->hubungan_keluarga }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Jenis Kelamin</span>
                        <span class="text-base capitalize text-gray-900">{{ $penduduk->kelamin }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Tempat / Tanggal Lahir</span>
                        <span class="text-base text-gray-900">{{ $penduduk->tempatlahir }} / {{ \Carbon\Carbon::parse($penduduk->tanggallahir)->translatedFormat('d F Y') }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Golongan Darah</span>
                        <span class="text-base font-bold text-gray-900">{{ $penduduk->goldar ?? 'Tidak Tahu' }}</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Latar Belakang & Kontak -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-50 bg-gray-50/30">
                    <div class="flex items-center">
                        <div class="p-2.5 bg-green-500/10 rounded-lg mr-4">
                            <i class="mdi mdi-book-open-outline text-green-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">Latar Belakang & Kontak</h3>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Agama</span>
                        <span class="text-base text-gray-900 capitalize">{{ $penduduk->agama ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Pendidikan Terakhir</span>
                        <span class="text-base text-gray-900 capitalize">{{ $penduduk->pendidikan ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Pekerjaan</span>
                        <span class="text-base text-gray-900 capitalize">{{ $penduduk->pekerjaan ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Status Perkawinan</span>
                        <span class="text-base text-gray-900 capitalize">{{ $penduduk->status_kawin }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Nama Ayah</span>
                        <span class="text-base text-gray-900">{{ $penduduk->nama_ayah ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Nama Ibu</span>
                        <span class="text-base text-gray-900">{{ $penduduk->nama_ibu ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Nomor Telepon</span>
                        <span class="text-base text-gray-900">{{ $penduduk->telepon ?? '-' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Side: Address & Health Card -->
        <div class="lg:col-span-4 space-y-8">
            
            <!-- Card: Alamat Domisili -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-500/10 rounded-lg mr-3">
                            <i class="mdi mdi-map-marker text-purple-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Alamat Domisili</h3>
                    </div>
                </div>
                <div class="p-6 space-y-4 text-sm text-gray-600">
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Dusun</span>
                        <span class="text-sm font-bold text-gray-900">{{ $penduduk->dusun ?? '-' }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">RT</span>
                            <span class="text-sm font-bold text-gray-900">{{ $penduduk->rt ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">RW</span>
                            <span class="text-sm font-bold text-gray-900">{{ $penduduk->rw ?? '-' }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-xs font-bold text-gray-400 uppercase">Alamat Lengkap</span>
                        <span class="text-sm text-gray-900 block mt-1 leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $penduduk->alamat ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Card: Jaminan Kesehatan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                    <div class="flex items-center">
                        <div class="p-2 bg-teal-500/10 rounded-lg mr-3">
                            <i class="mdi mdi-shield-check text-teal-600"></i>
                        </div>
                        <h3 class="font-bold text-gray-800">Jaminan Kesehatan</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="p-4 rounded-xl flex items-center justify-between {{ $penduduk->bpjs ? 'bg-green-50 border border-green-200 text-green-700' : 'bg-red-50 border border-red-200 text-red-700' }}">
                        <div class="flex items-center gap-2">
                            <i class="mdi {{ $penduduk->bpjs ? 'mdi-check-circle' : 'mdi-close-circle' }} text-xl"></i>
                            <span class="font-bold text-sm">BPJS Kesehatan</span>
                        </div>
                        <span class="text-xs uppercase font-extrabold px-2.5 py-1 rounded-full {{ $penduduk->bpjs ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $penduduk->bpjs ? 'Aktif / Punya' : 'Tidak Punya' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layout>
