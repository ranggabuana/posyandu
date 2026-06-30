@extends('layouts.public')

@section('title', 'Layanan & Aduan')

@section('content')
<section class="hero-bg relative min-h-[40vh] flex items-center pt-28 pb-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
    <div class="inline-flex items-center justify-center gap-2 section-badge floating" style="margin-bottom: 1.5rem;">
      <span style="color: var(--teal);">Hubungi Kami</span>
    </div>
    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-tight mb-6" style="color: var(--deep);">
      Layanan & <span style="background: linear-gradient(135deg, var(--teal), var(--mint)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Aduan</span>
    </h1>
    <p class="text-lg md:text-xl max-w-2xl mx-auto font-medium" style="color: rgba(2,52,48,0.7);">
      Kami siap mendengar dan melayani Anda. Silakan sampaikan laporan, pertanyaan, atau saran melalui formulir di bawah ini.
    </p>
  </div>
  <!-- Wavy divider bottom -->
  <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none" style="transform: translateY(1px);">
    <svg class="w-full h-10 sm:h-16 lg:h-24" viewBox="0 0 1200 120" preserveAspectRatio="none">
      <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.43,122.9,122,185.12,106.67Z" fill="var(--cream)"></path>
    </svg>
  </div>
</section>

<section class="py-16" style="background-color: var(--cream);">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-3 gap-12">
      
      <!-- Left: Contact Information -->
      <div class="space-y-8">
        <div class="reveal">
          <h2 class="text-3xl font-black mb-6" style="color: var(--deep);">Informasi <span class="gradient-text">Kontak</span></h2>
          <p class="text-gray-600 mb-8 font-medium">Tim kader kami siap membantu Anda pada jam operasional layanan.</p>
        </div>

        <div class="grid gap-4">
          <div class="reveal bg-white p-6 rounded-3xl border border-mint-100 flex items-start gap-4 transition-all hover:shadow-xl hover:shadow-mint-500/5" style="border-color: rgba(62,207,142,0.15);">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shrink-0" style="background: rgba(62,207,142,0.1);">📍</div>
            <div>
              <h4 class="font-bold text-sm" style="color: var(--deep);">Alamat Kami</h4>
              <p class="text-xs text-gray-500 leading-relaxed mt-1">{{ $settings['alamat_desa'] ?? 'Jl. Raya Banjar No. 1, Desa Banjar' }}</p>
            </div>
          </div>

          <div class="reveal bg-white p-6 rounded-3xl border border-mint-100 flex items-start gap-4 transition-all hover:shadow-xl hover:shadow-mint-500/5" style="border-color: rgba(62,207,142,0.15);">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shrink-0" style="background: rgba(62,207,142,0.1);">📱</div>
            <div>
              <h4 class="font-bold text-sm" style="color: var(--deep);">WhatsApp Kader</h4>
              <a href="https://wa.me/{{ $settings['no_whatsapp'] ?? '6285123456789' }}" class="text-sm font-bold mt-1 inline-block" style="color: var(--teal);">+{{ $settings['no_whatsapp'] ?? '62 851-2345-6789' }}</a>
              <p class="text-[10px] text-gray-400 mt-0.5 uppercase tracking-wider font-bold">Senin – Sabtu, 07:00 – 17:00</p>
            </div>
          </div>

          <div class="reveal bg-white p-6 rounded-3xl border border-mint-100 flex items-start gap-4 transition-all hover:shadow-xl hover:shadow-mint-500/5" style="border-color: rgba(62,207,142,0.15);">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shrink-0" style="background: rgba(62,207,142,0.1);">📧</div>
            <div>
              <h4 class="font-bold text-sm" style="color: var(--deep);">Email Dukungan</h4>
              <a href="mailto:{{ $settings['email'] ?? 'posyandu.melati@gmail.com' }}" class="text-sm font-bold mt-1 inline-block" style="color: var(--teal);">{{ $settings['email'] ?? 'posyandu.melati@gmail.com' }}</a>
              <p class="text-[10px] text-gray-400 mt-0.5 uppercase tracking-wider font-bold">Respon cepat dalam 24 jam</p>
            </div>
          </div>
        </div>

        <!-- Decorative Info Card -->
        <div class="reveal rounded-3xl p-8 relative overflow-hidden text-white" style="background: linear-gradient(135deg, var(--deep), var(--teal));">
          <div class="absolute -right-6 -bottom-6 text-9xl opacity-10 rotate-12">🏥</div>
          <h4 class="font-black text-xl mb-3 relative z-10">Layanan Terpadu</h4>
          <p class="text-sm opacity-80 leading-relaxed relative z-10">Setiap pesan yang Anda kirimkan akan ditelaah oleh tim kesehatan kami untuk memberikan solusi terbaik bagi keluarga Anda.</p>
        </div>
      </div>

      <!-- Right: Redesigned Form aligned with Laporan Masyarakat -->
      <div class="lg:col-span-2 reveal">
        <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-2xl shadow-mint-900/5 border border-mint-100" style="border-color: rgba(62,207,142,0.15);">
          <div class="mb-10">
            <h3 class="text-2xl font-black mb-2" style="color: var(--deep);">Formulir Laporan Masyarakat</h3>
            <p class="text-sm text-gray-500 font-medium">Sampaikan keluhan, saran, atau laporan terkait layanan kami.</p>
          </div>

          <form action="{{ route('public.aduan.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="daftarForm">
            @csrf
            <!-- Category Selection -->
            <div>
              <label class="block text-sm font-bold mb-4" style="color: var(--deep);">Pilih Jenis Keperluan *</label>
              <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                @foreach([
                  ['id' => 'Bidang Pendidikan', 'icon' => '🎓', 'label' => 'Pendidikan'],
                  ['id' => 'Bidang Pekerjaan Umum', 'icon' => '🏗️', 'label' => 'Pekerjaan Umum'],
                  ['id' => 'Bidang Perumahan Rakyat', 'icon' => '🏠', 'label' => 'Perumahan Rakyat'],
                  ['id' => 'Bidang Trantibum Linmas', 'icon' => '🛡️', 'label' => 'Trantibum Linmas'],
                  ['id' => 'Bidang Sosial', 'icon' => '🤝', 'label' => 'Sosial']
                ] as $svc)
                <label class="relative cursor-pointer group">
                  <input type="radio" name="kategori" value="{{ $svc['id'] }}" class="peer sr-only" required {{ old('kategori') == $svc['id'] ? 'checked' : '' }}>
                  <div class="rounded-2xl border-2 p-4 text-center transition-all peer-checked:border-[var(--mint)] peer-checked:bg-[rgba(62,207,142,0.1)] hover:bg-gray-50 border-gray-100 peer-checked:shadow-lg peer-checked:shadow-[rgba(62,207,142,0.15)]">
                    <div class="text-2xl mb-1 transform group-hover:scale-110 transition-transform">{{ $svc['icon'] }}</div>
                    <div class="text-[10px] font-bold uppercase tracking-wide text-gray-500 group-hover:text-[var(--teal)] peer-checked:text-[var(--teal)]">{{ $svc['label'] }}</div>
                  </div>
                </label>
                @endforeach
              </div>
              @error('kategori') <p class="text-[10px] text-red-500 mt-2 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-6 pt-4">
              <!-- Hari/Tanggal -->
              <div class="relative">
                <input type="date" name="hari_tanggal" id="hari_tanggal" value="{{ old('hari_tanggal') }}" 
                       class="peer w-full px-5 py-4 rounded-2xl border-2 outline-none transition-all focus:border-mint @error('hari_tanggal') border-red-400 @enderror" 
                       style="border-color: #f3f4f6; color: var(--deep); background: #fafafa;" required />
                <label for="hari_tanggal" class="absolute left-5 -top-2.5 bg-white px-2 text-[10px] font-black uppercase tracking-widest text-gray-400 transition-all peer-focus:text-teal">Hari / Tanggal *</label>
                @error('hari_tanggal') <p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
              </div>

              <!-- Nama Lengkap -->
              <div class="relative">
                <input type="text" name="nama_pelapor" id="nama_pelapor" value="{{ old('nama_pelapor') }}" class="peer w-full px-5 py-4 rounded-2xl border-2 outline-none transition-all placeholder-transparent focus:border-mint @error('nama_pelapor') border-red-400 @enderror" style="border-color: #f3f4f6; color: var(--deep); background: #fafafa;" placeholder="Nama Lengkap" required />
                <label for="nama_pelapor" class="absolute left-5 -top-2.5 bg-white px-2 text-[10px] font-black uppercase tracking-widest text-gray-400 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:font-bold peer-focus:-top-2.5 peer-focus:text-[10px] peer-focus:text-teal">Nama Lengkap *</label>
                @error('nama_pelapor') <p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
              </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-6">
              <!-- No. KTP -->
              <div class="relative">
                <input type="text" name="nik_pelapor" id="nik_pelapor" value="{{ old('nik_pelapor') }}" 
                       maxlength="16" minlength="16" pattern="\d{16}"
                       class="peer w-full px-5 py-4 rounded-2xl border-2 outline-none transition-all placeholder-transparent focus:border-mint @error('nik_pelapor') border-red-400 @enderror" style="border-color: #f3f4f6; color: var(--deep); background: #fafafa;" placeholder="No. KTP" required />
                <label for="nik_pelapor" class="absolute left-5 -top-2.5 bg-white px-2 text-[10px] font-black uppercase tracking-widest text-gray-400 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:font-bold peer-focus:-top-2.5 peer-focus:text-[10px] peer-focus:text-teal">No. KTP (16 Digit) *</label>
                @error('nik_pelapor') <p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
              </div>

              <!-- No. HP -->
              <div class="relative">
                <input type="tel" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}" 
                       maxlength="20"
                       class="peer w-full px-5 py-4 rounded-2xl border-2 outline-none transition-all placeholder-transparent focus:border-mint @error('no_telepon') border-red-400 @enderror" style="border-color: #f3f4f6; color: var(--deep); background: #fafafa;" placeholder="No. HP" required />
                <label for="no_telepon" class="absolute left-5 -top-2.5 bg-white px-2 text-[10px] font-black uppercase tracking-widest text-gray-400 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:font-bold peer-focus:-top-2.5 peer-focus:text-[10px] peer-focus:text-teal">No. HP *</label>
                @error('no_telepon') <p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
              </div>
            </div>

            <!-- Posyandu Dropdown -->
            <div class="relative">
              <select name="posyandu_id" id="posyandu_id" class="peer w-full px-5 py-4 rounded-2xl border-2 outline-none appearance-none transition-all focus:border-mint bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25rem_1.25rem] bg-[right_1.25rem_center] bg-no-repeat @error('posyandu_id') border-red-400 @enderror" style="border-color: #f3f4f6; color: var(--deep); background: #fafafa;" required>
                <option value="">Pilih Posyandu</option>
                @foreach($posyandus as $p)
                  <option value="{{ $p->id }}" {{ old('posyandu_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                @endforeach
              </select>
              <label for="posyandu_id" class="absolute left-5 -top-2.5 bg-white px-2 text-[10px] font-black uppercase tracking-widest text-gray-400 transition-all peer-focus:text-teal">Posyandu *</label>
              @error('posyandu_id') <p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <!-- Alamat -->
            <div class="relative">
              <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}" 
                     class="peer w-full px-5 py-4 rounded-2xl border-2 outline-none transition-all placeholder-transparent focus:border-mint @error('alamat') border-red-400 @enderror" style="border-color: #f3f4f6; color: var(--deep); background: #fafafa;" placeholder="Alamat" required />
              <label for="alamat" class="absolute left-5 -top-2.5 bg-white px-2 text-[10px] font-black uppercase tracking-widest text-gray-400 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:font-bold peer-focus:-top-2.5 peer-focus:text-[10px] peer-focus:text-teal">Alamat *</label>
              @error('alamat') <p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <!-- Keterangan -->
            <div class="relative">
              <textarea name="isi_laporan" id="isi_laporan" rows="4" class="peer w-full px-5 py-4 rounded-2xl border-2 outline-none transition-all placeholder-transparent focus:border-mint resize-none @error('isi_laporan') border-red-400 @enderror" style="border-color: #f3f4f6; color: var(--deep); background: #fafafa;" placeholder="Keterangan" required>{{ old('isi_laporan') }}</textarea>
              <label for="isi_laporan" class="absolute left-5 -top-2.5 bg-white px-2 text-[10px] font-black uppercase tracking-widest text-gray-400 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:font-bold peer-focus:-top-2.5 peer-focus:text-[10px] peer-focus:text-teal">Keterangan *</label>
              @error('isi_laporan') <p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-2">Foto Bukti (Opsional)</label>
                    <input type="file" name="foto_bukti" accept="image/*" class="w-full px-5 py-3 rounded-2xl border-2 outline-none transition-all focus:border-mint @error('foto_bukti') border-red-400 @enderror" style="border-color: #f3f4f6; color: var(--deep); background: #fafafa;" />
                    @error('foto_bukti') <p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="relative group">
                    <input type="number" name="captcha" id="captcha" class="peer w-full px-5 py-4 rounded-2xl border-2 outline-none transition-all placeholder-transparent focus:border-mint @error('captcha') border-red-400 @enderror" style="border-color: #f3f4f6; color: var(--deep); background: #fafafa;" placeholder="Captcha" required />
                    <label for="captcha" id="captcha-label" class="absolute left-5 -top-2.5 bg-white px-2 text-[10px] font-black uppercase tracking-widest text-teal transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:font-bold peer-focus:-top-2.5 peer-focus:text-[10px] peer-focus:text-teal">Keamanan: {{ $captcha_question }} *</label>
                    
                    <button type="button" onclick="refreshCaptcha()" class="absolute right-4 top-1/2 -translate-y-1/2 w-9 h-9 rounded-xl flex items-center justify-center bg-mint/10 text-teal hover:bg-mint hover:text-white transition-all shadow-sm" title="Ganti Pertanyaan">
                        <i class="mdi mdi-refresh text-lg" id="refresh-icon"></i>
                    </button>
                    @error('captcha') <p class="text-[10px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <script>
                function refreshCaptcha() {
                    const icon = document.getElementById('refresh-icon');
                    const label = document.getElementById('captcha-label');
                    icon.classList.add('animate-spin');

                    fetch('{{ route("public.aduan.refresh-captcha") }}')
                        .then(response => response.json())
                        .then(data => {
                            label.innerText = 'Keamanan: ' + data.question + ' *';
                            document.getElementById('captcha').value = '';
                            setTimeout(() => icon.classList.remove('animate-spin'), 500);
                        });
                }
            </script>

            <button type="submit" class="group relative w-full py-5 rounded-2xl font-black text-white text-sm uppercase tracking-widest transition-all overflow-hidden shadow-xl" style="background: linear-gradient(135deg, var(--teal), var(--mint));">
              <div class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-20 transition-opacity"></div>
              <span class="relative flex items-center justify-center gap-3">
                Kirim Laporan Sekarang
                <svg class="w-5 h-5 transform group-hover:translate-x-2 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
              </span>
            </button>
          </form>

          @if(session('success'))
          <div id="successMessage" class="absolute inset-0 bg-white z-30 flex flex-col items-center justify-center rounded-[2.5rem] p-10 text-center">
            <div class="w-24 h-24 rounded-full flex items-center justify-center mb-8 shadow-2xl shadow-mint-500/20" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0);">
              <svg class="w-12 h-12" style="color: var(--teal);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 class="text-3xl font-black mb-4" style="color: var(--deep);">Berhasil Terkirim!</h3>
            <p class="text-base text-gray-500 font-medium mb-10 max-w-sm mx-auto">
              Laporan Anda sudah kami terima. Tim kami akan segera menelaah dan memberikan tindak lanjut sesegera mungkin.
            </p>
            <a href="{{ route('public.aduan') }}" class="px-8 py-3 rounded-xl border-2 border-mint text-teal font-black text-xs uppercase tracking-widest hover:bg-mint/5 transition-all">Kirim Laporan Lain</a>
          </div>
          @endif

        </div>
      </div>

    </div>
  </div>
</section>

{{-- ===================== CEK STATUS ADUAN ===================== --}}
<section class="py-16 relative overflow-hidden" style="background: linear-gradient(180deg, var(--cream) 0%, #f0fdf7 100%);">
  <!-- Decorative elements -->
  <div class="absolute top-0 left-0 w-72 h-72 rounded-full opacity-30" style="background: radial-gradient(circle, rgba(62,207,142,0.15) 0%, transparent 70%); transform: translate(-30%, -30%);"></div>
  <div class="absolute bottom-0 right-0 w-96 h-96 rounded-full opacity-20" style="background: radial-gradient(circle, rgba(15,154,123,0.1) 0%, transparent 70%); transform: translate(20%, 20%);"></div>

  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="text-center mb-12 reveal">
      <div class="inline-flex items-center justify-center gap-2 section-badge floating" style="margin-bottom: 1.5rem;">
        <span style="color: var(--teal);">Lacak Aduan</span>
      </div>
      <h2 class="text-3xl md:text-4xl font-black mb-4" style="color: var(--deep);">
        Cek <span style="background: linear-gradient(135deg, var(--teal), var(--mint)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Status Aduan</span>
      </h2>
      <p class="text-gray-500 font-medium max-w-xl mx-auto">
        Masukkan NIK yang Anda gunakan saat mengirim laporan untuk melihat status dan tanggapan dari admin.
      </p>
    </div>

    <!-- Search Box -->
    <div class="reveal bg-white rounded-[2rem] p-6 md:p-8 shadow-2xl shadow-mint-900/5 border mb-8" style="border-color: rgba(62,207,142,0.15);">
      <div class="flex flex-col sm:flex-row gap-4">
        <div class="relative flex-1">
          <input type="text" id="cek-nik" maxlength="16" minlength="16" pattern="\d{16}" 
                 class="w-full px-5 py-4 rounded-2xl border-2 outline-none transition-all placeholder-transparent peer" 
                 style="border-color: #f3f4f6; color: var(--deep); background: #fafafa;" 
                 placeholder="Masukkan NIK" />
          <label for="cek-nik" class="absolute left-5 -top-2.5 bg-white px-2 text-[10px] font-black uppercase tracking-widest text-gray-400 transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-placeholder-shown:font-bold peer-focus:-top-2.5 peer-focus:text-[10px] peer-focus:text-teal">
            NIK Pelapor (16 Digit)
          </label>
        </div>
        <button type="button" onclick="cekStatus()" id="btn-cek-status"
                class="group relative px-8 py-4 rounded-2xl font-black text-white text-sm uppercase tracking-widest transition-all overflow-hidden shadow-lg hover:shadow-xl shrink-0" 
                style="background: linear-gradient(135deg, var(--deep), var(--teal));">
          <div class="absolute inset-0 w-full h-full bg-white opacity-0 group-hover:opacity-10 transition-opacity"></div>
          <span class="relative flex items-center justify-center gap-2" id="btn-cek-text">
            <i class="mdi mdi-magnify text-lg"></i>
            Cek Status
          </span>
        </button>
      </div>
      <p id="cek-error" class="text-red-500 text-xs mt-3 font-bold hidden"></p>
    </div>

    <!-- Results Container -->
    <div id="hasil-status" class="space-y-6 hidden">
      <!-- Header result info -->
      <div id="hasil-header" class="flex items-center justify-between px-2">
        <p class="text-sm font-bold" style="color: var(--deep);"><span id="jumlah-laporan">0</span> laporan ditemukan</p>
      </div>
      
      <!-- Cards will be injected here -->
      <div id="hasil-cards" class="space-y-5"></div>
    </div>

    <!-- Empty state -->
    <div id="hasil-kosong" class="hidden">
      <div class="bg-white rounded-[2rem] p-12 text-center shadow-lg border" style="border-color: rgba(62,207,142,0.15);">
        <div class="w-20 h-20 rounded-full mx-auto mb-6 flex items-center justify-center" style="background: rgba(62,207,142,0.1);">
          <i class="mdi mdi-file-search-outline text-4xl" style="color: var(--teal);"></i>
        </div>
        <h4 class="text-xl font-black mb-2" style="color: var(--deep);">Tidak Ada Laporan</h4>
        <p class="text-gray-500 font-medium text-sm max-w-sm mx-auto">Tidak ditemukan laporan dengan NIK tersebut. Pastikan NIK yang Anda masukkan sudah benar.</p>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
function cekStatus() {
    const nik = document.getElementById('cek-nik').value.trim();
    const errorEl = document.getElementById('cek-error');
    const hasilStatus = document.getElementById('hasil-status');
    const hasilKosong = document.getElementById('hasil-kosong');
    const hasilCards = document.getElementById('hasil-cards');
    const btnText = document.getElementById('btn-cek-text');
    const btn = document.getElementById('btn-cek-status');

    // Reset
    errorEl.classList.add('hidden');

    // Validation
    if (nik.length !== 16 || !/^\d{16}$/.test(nik)) {
        errorEl.textContent = 'NIK harus terdiri dari 16 digit angka.';
        errorEl.classList.remove('hidden');
        return;
    }

    // Loading state
    btn.disabled = true;
    btn.style.opacity = '0.7';
    btnText.innerHTML = '<i class="mdi mdi-loading mdi-spin text-lg"></i> Mencari...';

    fetch('{{ route("public.aduan.cek-status") }}?nik=' + nik)
        .then(function(res) { return res.json(); })
        .then(function(data) {
            btn.disabled = false;
            btn.style.opacity = '1';
            btnText.innerHTML = '<i class="mdi mdi-magnify text-lg"></i> Cek Status';

            if (data.length === 0) {
                hasilStatus.classList.add('hidden');
                hasilKosong.classList.remove('hidden');
                return;
            }

            hasilKosong.classList.add('hidden');
            hasilStatus.classList.remove('hidden');
            document.getElementById('jumlah-laporan').textContent = data.length;

            // Build cards
            var cardsHTML = '';
            for (var i = 0; i < data.length; i++) {
                var item = data[i];
                cardsHTML += buildCard(item, i);
            }
            hasilCards.innerHTML = cardsHTML;
        })
        .catch(function(err) {
            btn.disabled = false;
            btn.style.opacity = '1';
            btnText.innerHTML = '<i class="mdi mdi-magnify text-lg"></i> Cek Status';
            errorEl.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
            errorEl.classList.remove('hidden');
        });
}

function buildCard(item, index) {
    var statusConfig = {
        'baru': { label: 'Baru', bg: 'background: rgba(59,130,246,0.1)', color: 'color: #3b82f6', icon: 'mdi-clock-outline', step: 1 },
        'diproses': { label: 'Diproses', bg: 'background: rgba(245,158,11,0.1)', color: 'color: #f59e0b', icon: 'mdi-progress-wrench', step: 2 },
        'selesai': { label: 'Selesai', bg: 'background: rgba(16,185,129,0.1)', color: 'color: #10b981', icon: 'mdi-check-circle', step: 3 },
        'ditolak': { label: 'Ditolak', bg: 'background: rgba(239,68,68,0.1)', color: 'color: #ef4444', icon: 'mdi-close-circle', step: 0 }
    };
    var s = statusConfig[item.status] || statusConfig['baru'];

    var date = new Date(item.created_at);
    var months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    var formattedDate = date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
    var hours = String(date.getHours()).padStart(2, '0');
    var minutes = String(date.getMinutes()).padStart(2, '0');
    var formattedTime = hours + ':' + minutes;

    var kategoriIcons = { 
        'Bidang Pendidikan': '🎓', 
        'Bidang Pekerjaan Umum': '🏗️', 
        'Bidang Perumahan Rakyat': '🏠', 
        'Bidang Trantibum Linmas': '🛡️', 
        'Bidang Sosial': '🤝' 
    };
    var kategoriIcon = kategoriIcons[item.kategori] || '📋';

    // Progress steps
    var progressHTML = '';
    if (item.status !== 'ditolak') {
        var steps = ['Terkirim', 'Diproses', 'Selesai'];
        progressHTML = '<div class="flex items-center gap-0 mt-6 mb-2">';
        for (var j = 0; j < steps.length; j++) {
            var active = (j + 1) <= s.step;
            var dotStyle = active 
                ? 'background: linear-gradient(135deg, var(--teal), var(--mint)); color: white; box-shadow: 0 4px 12px rgba(15,154,123,0.3);' 
                : 'background: #f3f4f6; color: #9ca3af;';
            var dotContent = active ? '<i class="mdi mdi-check text-sm"></i>' : (j + 1);
            var labelStyle = active ? 'color: var(--teal);' : 'color: #9ca3af;';
            var lineStyle = (active && (j + 1) < s.step) 
                ? 'background: linear-gradient(90deg, var(--teal), var(--mint));' 
                : 'background: #e5e7eb;';

            progressHTML += '<div class="flex items-center ' + (j < steps.length - 1 ? 'flex-1' : '') + '">';
            progressHTML += '<div class="flex flex-col items-center">';
            progressHTML += '<div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black" style="' + dotStyle + '">' + dotContent + '</div>';
            progressHTML += '<span class="text-[9px] font-bold uppercase tracking-wider mt-1.5" style="' + labelStyle + '">' + steps[j] + '</span>';
            progressHTML += '</div>';
            if (j < steps.length - 1) {
                progressHTML += '<div class="flex-1 h-0.5 mx-2 rounded-full mb-5" style="' + lineStyle + '"></div>';
            }
            progressHTML += '</div>';
        }
        progressHTML += '</div>';
    } else {
        progressHTML = '<div class="mt-6 mb-2 flex items-center gap-2 px-4 py-3 rounded-xl" style="background: rgba(239,68,68,0.05); border: 1px solid rgba(239,68,68,0.15);">';
        progressHTML += '<i class="mdi mdi-close-circle text-red-500"></i>';
        progressHTML += '<span class="text-xs font-bold text-red-600">Laporan ini telah ditolak oleh admin</span>';
        progressHTML += '</div>';
    }

    // Balasan section
    var balasanHTML = '';
    if (item.balasan) {
        balasanHTML = '<div class="mt-5 rounded-2xl p-5 relative overflow-hidden" style="background: linear-gradient(135deg, rgba(15,154,123,0.04), rgba(62,207,142,0.08)); border: 1px solid rgba(62,207,142,0.15);">';
        balasanHTML += '<div class="absolute top-3 right-3 text-3xl opacity-10">💬</div>';
        balasanHTML += '<div class="flex items-center gap-2 mb-3">';
        balasanHTML += '<div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, var(--teal), var(--mint));">';
        balasanHTML += '<i class="mdi mdi-reply text-white text-xs"></i>';
        balasanHTML += '</div>';
        balasanHTML += '<span class="text-xs font-black uppercase tracking-wider" style="color: var(--teal);">Tanggapan Admin</span>';
        balasanHTML += '</div>';
        balasanHTML += '<p class="text-sm leading-relaxed" style="color: var(--deep);">' + escapeHtml(item.balasan) + '</p>';
        balasanHTML += '</div>';
    } else if (item.status === 'baru') {
        balasanHTML = '<div class="mt-5 rounded-2xl p-4 flex items-center gap-3" style="background: rgba(59,130,246,0.04); border: 1px dashed rgba(59,130,246,0.2);">';
        balasanHTML += '<i class="mdi mdi-information-outline text-blue-400"></i>';
        balasanHTML += '<span class="text-xs text-blue-500 font-medium">Laporan Anda sedang menunggu untuk ditinjau oleh admin.</span>';
        balasanHTML += '</div>';
    } else if (item.status === 'diproses') {
        balasanHTML = '<div class="mt-5 rounded-2xl p-4 flex items-center gap-3" style="background: rgba(245,158,11,0.04); border: 1px dashed rgba(245,158,11,0.2);">';
        balasanHTML += '<i class="mdi mdi-progress-clock text-amber-400"></i>';
        balasanHTML += '<span class="text-xs text-amber-600 font-medium">Laporan sedang dalam proses penanganan. Tanggapan akan segera diberikan.</span>';
        balasanHTML += '</div>';
    }

    var card = '<div class="bg-white rounded-[2rem] shadow-lg border overflow-hidden transition-all hover:shadow-xl" style="border-color: rgba(62,207,142,0.12); animation: slideUp 0.5s ease forwards; animation-delay: ' + (index * 100) + 'ms; opacity: 0;">';
    
    // Card Header
    card += '<div class="px-6 md:px-8 pt-6 md:pt-8 pb-4 flex flex-col sm:flex-row sm:items-start justify-between gap-4">';
    card += '<div class="flex items-start gap-4 flex-1 min-w-0">';
    card += '<div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shrink-0" style="background: rgba(62,207,142,0.1);">' + kategoriIcon + '</div>';
    card += '<div class="min-w-0 flex-1">';
    card += '<div class="flex items-center gap-2 flex-wrap mb-1">';
    card += '<span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg" style="' + s.bg + '; ' + s.color + ';"><i class="mdi ' + s.icon + ' mr-1"></i>' + s.label + '</span>';
    card += '<span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-lg bg-gray-100 text-gray-500">' + escapeHtml(item.kategori) + '</span>';
    card += '</div>';
    card += '<p class="text-sm font-bold truncate" style="color: var(--deep);">' + escapeHtml(item.nama_pelapor) + '</p>';
    card += '<p class="text-[11px] text-gray-400 font-medium mt-0.5"><i class="mdi mdi-calendar-clock mr-1"></i>' + formattedDate + ' &bull; ' + formattedTime + '</p>';
    card += '</div></div></div>';

    // Card Body
    card += '<div class="px-6 md:px-8 pb-6 md:pb-8">';
    card += '<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4 text-xs text-gray-500 font-semibold">';
    if (item.hari_tanggal) {
        var htDate = new Date(item.hari_tanggal);
        var htFormatted = htDate.getDate() + ' ' + months[htDate.getMonth()] + ' ' + htDate.getFullYear();
        card += '<div><span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Hari / Tanggal Kejadian</span>' + htFormatted + '</div>';
    }
    if (item.alamat) {
        card += '<div><span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Alamat</span>' + escapeHtml(item.alamat) + '</div>';
    }
    var posyanduName = (item.posyandu && item.posyandu.nama) ? item.posyandu.nama : '-';
    card += '<div><span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Posyandu Tujuan</span>' + escapeHtml(posyanduName) + '</div>';
    card += '</div>';
    card += '<div class="rounded-2xl p-4 mb-1" style="background: #fafafa; border: 1px solid #f3f4f6;">';
    card += '<p class="text-sm leading-relaxed text-gray-700">' + escapeHtml(item.isi_laporan) + '</p>';
    card += '</div>';
    card += progressHTML;
    card += balasanHTML;
    card += '</div>';

    card += '</div>';
    return card;
}

function escapeHtml(text) {
    if (!text) return '';
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}

// Allow Enter key on NIK input
document.getElementById('cek-nik').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        cekStatus();
    }
});
</script>
@endsection
