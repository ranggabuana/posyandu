@extends('layouts.public')
@section('title', 'Beranda')
@section('content')
<!-- ===================== HERO ===================== -->
<section class="hero-bg relative min-h-screen flex items-center pt-20 overflow-hidden">

  <!-- Decorative blobs background -->
  <div class="absolute top-10 -left-20 w-96 h-96 blob1 floating-slow opacity-30" style="background: radial-gradient(circle, rgba(62,207,142,0.4) 0%, transparent 70%);"></div>
  <div class="absolute bottom-10 -right-20 w-80 h-80 blob2 floating opacity-25" style="background: radial-gradient(circle, rgba(15,154,123,0.35) 0%, transparent 70%);"></div>
  <div class="absolute top-1/2 left-1/3 w-64 h-64 rounded-full opacity-10" style="background: var(--gold);"></div>

  <!-- Dot pattern overlay -->
  <div class="absolute inset-0 dot-pattern opacity-40 pointer-events-none"></div>

  <!-- Floating health icons -->
  <div class="absolute top-28 right-16 floating hidden lg:flex w-12 h-12 rounded-2xl items-center justify-center shadow-lg" style="background: white; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
    <span class="text-2xl">💊</span>
  </div>
  <div class="absolute top-44 right-36 floating-rev hidden lg:flex w-10 h-10 rounded-xl items-center justify-center shadow-lg" style="background: white; animation-delay: 1s; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
    <span class="text-xl">❤️</span>
  </div>
  <div class="absolute bottom-44 left-16 floating-slow hidden lg:flex w-12 h-12 rounded-2xl items-center justify-center shadow-lg" style="background: white; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
    <span class="text-2xl">🩺</span>
  </div>
  <div class="absolute bottom-32 right-24 floating hidden lg:flex w-10 h-10 rounded-xl items-center justify-center shadow-lg" style="background: white; animation-delay: 2s; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
    <span class="text-xl">🌿</span>
  </div>
  <div class="absolute top-36 left-1/2 floating-rev hidden lg:flex w-10 h-10 rounded-xl items-center justify-center shadow-lg" style="background: white; animation-delay: 0.5s; box-shadow: 0 8px 24px rgba(0,0,0,0.08);">
    <span class="text-xl">⚕️</span>
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid lg:grid-cols-2 gap-12 items-center relative z-10">

    <!-- Left: Text -->
    <div>
      <div class="section-badge anim-1">
        <span>🏥</span>
        <span>Kesehatan Keluarga Indonesia</span>
      </div>

      <h1 class="anim-2 text-5xl sm:text-6xl lg:text-7xl font-black leading-none mb-6" style="color: var(--deep);">
        Sehat Bersama,<br/>
        <span class="gradient-text">Tumbuh</span><br/>
        Bersama.
      </h1>

      <p class="anim-3 text-lg sm:text-xl leading-relaxed mb-8 max-w-md" style="color: rgba(2,52,48,0.7); font-weight: 400;">
        {{ $settings['moto'] ?? 'Posyandu Melati Sehat hadir untuk memberikan layanan kesehatan ibu, bayi, dan balita secara menyeluruh, terpadu, dan penuh kasih sayang.' }}
      </p>

      <div class="anim-4 flex flex-wrap gap-3 mb-10">
        <a href="#daftar" class="btn-primary">Daftar Pemeriksaan</a>
        <a href="#layanan" class="btn-outline">Lihat Layanan →</a>
      </div>

      <!-- Mini stats row -->
      <div class="anim-5 flex flex-wrap gap-6">
        <div>
          <div class="stat-num">{{ number_format($stats['total_penduduk'] ?? 0) }}</div>
          <div class="text-sm font-medium mt-0.5" style="color: rgba(2,52,48,0.6);">Penduduk Terdaftar</div>
        </div>
        <div class="w-px" style="background: rgba(62,207,142,0.3);"></div>
        <div>
          <div class="stat-num">{{ $posyandus->count() }}</div>
          <div class="text-sm font-medium mt-0.5" style="color: rgba(2,52,48,0.6);">Posyandu Aktif</div>
        </div>
        <div class="w-px" style="background: rgba(62,207,142,0.3);"></div>
        <div>
          <div class="stat-num">{{ \App\Models\User::count() }}</div>
          <div class="text-sm font-medium mt-0.5" style="color: rgba(2,52,48,0.6);">Kader Kesehatan</div>
        </div>
      </div>
    </div>

    <!-- Right: Illustration -->
    <div class="hero-illustration flex justify-center lg:justify-end">
      <div class="relative w-80 h-80 sm:w-96 sm:h-96 lg:w-[440px] lg:h-[440px]">

        <!-- Pulse rings behind main circle -->
        <div class="pulse-ring absolute inset-4 rounded-full" style="border: 3px solid rgba(62,207,142,0.3);"></div>
        <div class="pulse-ring absolute inset-0 rounded-full" style="border: 2px solid rgba(62,207,142,0.15); animation-delay: 0.8s;"></div>

        <!-- Main circle -->
        <div class="absolute inset-8 rounded-full overflow-hidden shadow-2xl" style="background: linear-gradient(135deg, rgba(62,207,142,0.15), rgba(15,154,123,0.1)); border: 3px solid rgba(62,207,142,0.25);">
          <!-- SVG illustration of family/health -->
          <div class="w-full h-full flex items-center justify-center">
            <svg viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4/5 h-4/5">
              <!-- Background circles -->
              <circle cx="150" cy="150" r="130" fill="rgba(62,207,142,0.06)"/>
              
              <!-- Ground -->
              <ellipse cx="150" cy="240" rx="100" ry="12" fill="rgba(62,207,142,0.15)"/>

              <!-- Doctor figure (center) -->
              <circle cx="150" cy="95" r="28" fill="#d1fae5"/>
              <circle cx="150" cy="88" r="18" fill="#0f9a7b"/>
              <!-- Face -->
              <circle cx="144" cy="86" r="2.5" fill="white"/>
              <circle cx="156" cy="86" r="2.5" fill="white"/>
              <path d="M145 93 Q150 97 155 93" stroke="white" stroke-width="1.5" fill="none" stroke-linecap="round"/>
              <!-- Doctor cap -->
              <rect x="136" y="74" width="28" height="8" rx="4" fill="#3ecf8e"/>
              <rect x="146" y="68" width="8" height="6" rx="2" fill="#3ecf8e"/>
              <!-- Stethoscope -->
              <path d="M160 95 Q170 95 170 105 Q170 115 160 115" stroke="#0f9a7b" stroke-width="2.5" fill="none" stroke-linecap="round"/>
              <circle cx="160" cy="117" r="4" fill="#0f9a7b"/>
              <!-- Body -->
              <rect x="130" y="115" width="40" height="55" rx="12" fill="#ecfdf5"/>
              <!-- White coat line -->
              <path d="M150 115 L150 170" stroke="#d1fae5" stroke-width="2"/>
              <!-- Cross on coat -->
              <rect x="142" y="130" width="3" height="12" rx="1.5" fill="#3ecf8e"/>
              <rect x="139" y="135" width="9" height="3" rx="1.5" fill="#3ecf8e"/>
              <!-- Arms -->
              <rect x="112" y="118" width="18" height="8" rx="4" fill="#ecfdf5"/>
              <rect x="170" y="118" width="18" height="8" rx="4" fill="#ecfdf5"/>
              <!-- Legs -->
              <rect x="133" y="168" width="14" height="30" rx="7" fill="#d1fae5"/>
              <rect x="153" y="168" width="14" height="30" rx="7" fill="#d1fae5"/>
              <!-- Shoes -->
              <ellipse cx="140" cy="200" rx="9" ry="5" fill="#0f9a7b"/>
              <ellipse cx="160" cy="200" rx="9" ry="5" fill="#0f9a7b"/>

              <!-- Mother figure (left) -->
              <circle cx="82" cy="108" r="18" fill="#fde68a"/>
              <!-- Face -->
              <circle cx="77" cy="106" r="2" fill="#92400e"/>
              <circle cx="87" cy="106" r="2" fill="#92400e"/>
              <path d="M78 112 Q82 115 86 112" stroke="#92400e" stroke-width="1.5" fill="none" stroke-linecap="round"/>
              <!-- Hair -->
              <path d="M67 104 Q64 90 82 86 Q100 86 97 104" fill="#f59e0b"/>
              <!-- Body -->
              <ellipse cx="82" cy="155" rx="22" ry="30" fill="#fef3c7"/>
              <!-- Baby in arms -->
              <circle cx="106" cy="150" r="10" fill="#fde68a"/>
              <circle cx="106" cy="147" r="7" fill="#fbbf24"/>
              <!-- Baby face -->
              <circle cx="103.5" cy="146" r="1.5" fill="#92400e"/>
              <circle cx="108.5" cy="146" r="1.5" fill="#92400e"/>
              <!-- Arm holding baby -->
              <path d="M100 142 Q108 138 112 148" stroke="#fef3c7" stroke-width="6" fill="none" stroke-linecap="round"/>
              <!-- Legs -->
              <rect x="73" y="182" width="10" height="22" rx="5" fill="#fde68a"/>
              <rect x="85" y="182" width="10" height="22" rx="5" fill="#fde68a"/>

              <!-- Child figure (right) -->
              <circle cx="218" cy="118" r="16" fill="#fde68a"/>
              <!-- Face -->
              <circle cx="213" cy="116" r="2" fill="#92400e"/>
              <circle cx="223" cy="116" r="2" fill="#92400e"/>
              <path d="M214 122 Q218 125 222 122" stroke="#92400e" stroke-width="1.5" fill="none" stroke-linecap="round"/>
              <!-- Body -->
              <rect x="206" y="133" width="24" height="38" rx="8" fill="#bfdbfe"/>
              <!-- T-shirt detail -->
              <path d="M206 142 L230 142" stroke="#93c5fd" stroke-width="1"/>
              <!-- Arms up (waving) -->
              <path d="M206 138 Q195 125 190 118" stroke="#fde68a" stroke-width="7" fill="none" stroke-linecap="round"/>
              <path d="M230 138 Q241 125 246 118" stroke="#fde68a" stroke-width="7" fill="none" stroke-linecap="round"/>
              <!-- Hands -->
              <circle cx="190" cy="118" r="5" fill="#fde68a"/>
              <circle cx="246" cy="118" r="5" fill="#fde68a"/>
              <!-- Legs -->
              <rect x="209" y="170" width="10" height="25" rx="5" fill="#fde68a"/>
              <rect x="221" y="170" width="10" height="25" rx="5" fill="#fde68a"/>

              <!-- Decorative elements -->
              <!-- Heart -->
              <path d="M40 50 C40 43 30 38 30 47 C30 52 40 60 40 60 C40 60 50 52 50 47 C50 38 40 43 40 50Z" fill="#ff6b6b" opacity="0.8"/>
              <!-- Star/sparkle -->
              <g transform="translate(240,45)" opacity="0.7">
                <path d="M8 0 L10 6 L16 6 L11 10 L13 16 L8 12 L3 16 L5 10 L0 6 L6 6Z" fill="#f5c842"/>
              </g>
              <!-- Leaf -->
              <path d="M55 210 C55 195 68 185 80 190 C68 190 60 200 55 210Z" fill="#3ecf8e" opacity="0.6"/>
              <path d="M245 210 C245 195 232 185 220 190 C232 190 240 200 245 210Z" fill="#3ecf8e" opacity="0.6"/>
            </svg>
          </div>
        </div>

        <!-- Floating badge cards -->
        <div class="absolute -bottom-4 -left-8 bg-white rounded-2xl px-4 py-3 shadow-xl floating" style="box-shadow: 0 12px 36px rgba(0,0,0,0.1);">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-base" style="background: rgba(62,207,142,0.15);">✅</div>
            <div>
              <div class="text-xs font-semibold" style="color: var(--deep);">Imunisasi Lengkap</div>
              <div class="text-xs" style="color: rgba(2,52,48,0.5);">Terjadwal & Terpantau</div>
            </div>
          </div>
        </div>

        <div class="absolute -top-4 -right-4 bg-white rounded-2xl px-4 py-3 shadow-xl floating-rev" style="box-shadow: 0 12px 36px rgba(0,0,0,0.1);">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-base" style="background: rgba(255,107,107,0.15);">❤️</div>
            <div>
              <div class="text-xs font-semibold" style="color: var(--deep);">{{ number_format(\App\Models\LaporanMasyarakat::count()) }} Laporan</div>
              <div class="text-xs" style="color: rgba(2,52,48,0.5);">Aduan Warga</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Wave divider -->
  <div class="wave-top" style="bottom: -1px; top: auto;">
    <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="display: block;">
      <path d="M0 60 L0 30 Q360 0 720 30 Q1080 60 1440 30 L1440 60 Z" fill="#ffffff"/>
    </svg>
  </div>
</section>


<!-- ===================== LAYANAN ===================== -->
<section id="layanan" class="py-24 bg-white relative overflow-hidden">
  <!-- Decorative background -->
  <div class="absolute top-0 right-0 w-64 h-64 rounded-full opacity-5 floating-slow" style="background: var(--teal); transform: translate(30%, -30%);"></div>
  <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full opacity-5 floating-rev" style="background: var(--mint); transform: translate(-30%, 30%);"></div>
  <div class="absolute top-1/2 left-4 w-12 h-12 rounded-full border-4 opacity-10 floating" style="border-color: var(--teal);"></div>
  <div class="absolute bottom-1/4 right-8 w-8 h-8 rounded-full opacity-10 floating-slow" style="background: var(--gold);"></div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16 reveal">
      <div class="section-badge">⚕️ Layanan Kami</div>
      <h2 class="text-4xl sm:text-5xl font-black mb-4" style="color: var(--deep);">
        Layanan <span class="gradient-text">Kesehatan Terpadu</span>
      </h2>
      <p class="text-lg max-w-xl mx-auto" style="color: rgba(2,52,48,0.6);">
        Kami menyediakan berbagai layanan kesehatan komprehensif untuk seluruh anggota keluarga Anda.
      </p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

      <!-- Service cards -->
      <div class="card-hover reveal rounded-3xl p-8 border" style="background: linear-gradient(135deg, #ecfdf5, #d1fae5); border-color: rgba(62,207,142,0.2);">
        <div class="icon-circle mb-6" style="background: white; box-shadow: 0 4px 16px rgba(62,207,142,0.2);">
          <span>👶</span>
        </div>
        <h3 class="text-xl font-bold mb-3" style="color: var(--deep);">Kesehatan Bayi & Balita</h3>
        <p class="text-sm leading-relaxed mb-4" style="color: rgba(2,52,48,0.65);">
          Pemantauan tumbuh kembang, penimbangan rutin, dan deteksi dini gangguan pertumbuhan anak.
        </p>
        <div class="flex flex-wrap gap-2">
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: var(--teal);">Penimbangan</span>
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: var(--teal);">Postur Gigi</span>
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: var(--teal);">Gizi</span>
        </div>
      </div>

      <div class="card-hover reveal rounded-3xl p-8 border" style="background: linear-gradient(135deg, #fff7ed, #fde68a33); border-color: rgba(245,200,66,0.25);">
        <div class="icon-circle mb-6" style="background: white; box-shadow: 0 4px 16px rgba(245,200,66,0.2);">
          <span>💉</span>
        </div>
        <h3 class="text-xl font-bold mb-3" style="color: var(--deep);">Imunisasi Lengkap</h3>
        <p class="text-sm leading-relaxed mb-4" style="color: rgba(2,52,48,0.65);">
          Program imunisasi dasar dan lanjutan sesuai jadwal Kementerian Kesehatan RI, gratis untuk warga.
        </p>
        <div class="flex flex-wrap gap-2">
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #d97706;">BCG</span>
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #d97706;">Polio</span>
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #d97706;">DPT-HB</span>
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #d97706;">Campak</span>
        </div>
      </div>

      <div class="card-hover reveal rounded-3xl p-8 border" style="background: linear-gradient(135deg, #fdf2f8, #fce7f3); border-color: rgba(244,114,182,0.2);">
        <div class="icon-circle mb-6" style="background: white; box-shadow: 0 4px 16px rgba(244,114,182,0.2);">
          <span>🤱</span>
        </div>
        <h3 class="text-xl font-bold mb-3" style="color: var(--deep);">Kesehatan Ibu Hamil</h3>
        <p class="text-sm leading-relaxed mb-4" style="color: rgba(2,52,48,0.65);">
          Pemeriksaan kehamilan (ANC), konseling gizi ibu hamil, persiapan persalinan, dan kelas ibu hamil.
        </p>
        <div class="flex flex-wrap gap-2">
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #db2777;">ANC</span>
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #db2777;">Kelas Ibu</span>
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #db2777;">Gizi Hamil</span>
        </div>
      </div>

      <div class="card-hover reveal rounded-3xl p-8 border" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border-color: rgba(59,130,246,0.2);">
        <div class="icon-circle mb-6" style="background: white; box-shadow: 0 4px 16px rgba(59,130,246,0.2);">
          <span>🩺</span>
        </div>
        <h3 class="text-xl font-bold mb-3" style="color: var(--deep);">Pemeriksaan Lansia</h3>
        <p class="text-sm leading-relaxed mb-4" style="color: rgba(2,52,48,0.65);">
          Skrining kesehatan warga lanjut usia: tekanan darah, gula darah, kolesterol, dan konsultasi kesehatan.
        </p>
        <div class="flex flex-wrap gap-2">
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #2563eb;">Tensi</span>
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #2563eb;">Gula Darah</span>
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #2563eb;">Kolesterol</span>
        </div>
      </div>

      <div class="card-hover reveal rounded-3xl p-8 border" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border-color: rgba(34,197,94,0.2);">
        <div class="icon-circle mb-6" style="background: white; box-shadow: 0 4px 16px rgba(34,197,94,0.2);">
          <span>🥗</span>
        </div>
        <h3 class="text-xl font-bold mb-3" style="color: var(--deep);">Konseling Gizi</h3>
        <p class="text-sm leading-relaxed mb-4" style="color: rgba(2,52,48,0.65);">
          Edukasi dan konseling nutrisi untuk balita stunting, ibu menyusui, dan warga dengan penyakit kronis.
        </p>
        <div class="flex flex-wrap gap-2">
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #16a34a;">Stunting</span>
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #16a34a;">PMT</span>
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #16a34a;">ASI Eksklusif</span>
        </div>
      </div>

      <div class="card-hover reveal rounded-3xl p-8 border" style="background: linear-gradient(135deg, #faf5ff, #ede9fe); border-color: rgba(139,92,246,0.2);">
        <div class="icon-circle mb-6" style="background: white; box-shadow: 0 4px 16px rgba(139,92,246,0.2);">
          <span>🧠</span>
        </div>
        <h3 class="text-xl font-bold mb-3" style="color: var(--deep);">Edukasi & Promkes</h3>
        <p class="text-sm leading-relaxed mb-4" style="color: rgba(2,52,48,0.65);">
          Program Promosi Kesehatan: penyuluhan PHBS, sanitasi, pencegahan penyakit menular dan tidak menular.
        </p>
        <div class="flex flex-wrap gap-2">
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #7c3aed;">PHBS</span>
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #7c3aed;">Sanitasi</span>
          <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: white; color: #7c3aed;">Penyuluhan</span>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- ===================== STATS BANNER ===================== -->
<section class="py-16 relative overflow-hidden" style="background: linear-gradient(135deg, var(--deep) 0%, var(--teal) 100%);">
  <!-- Decorations -->
  <div class="absolute inset-0 dot-pattern opacity-10"></div>
  <div class="absolute top-0 left-1/4 w-64 h-64 rounded-full opacity-10 floating-slow" style="background: var(--mint); filter: blur(40px);"></div>
  <div class="absolute bottom-0 right-1/4 w-48 h-48 rounded-full opacity-10 floating-rev" style="background: var(--gold); filter: blur(30px);"></div>
  <div class="absolute top-4 right-10 text-white opacity-10 text-4xl floating">✦</div>
  <div class="absolute bottom-4 left-10 text-white opacity-10 text-5xl floating-slow">✦</div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center text-white">
      <div class="reveal">
        <div class="text-4xl mb-3 opacity-90">👥</div>
        <div class="text-5xl font-black mb-2" style="font-family: 'Inter', sans-serif; color: var(--gold);">{{ number_format($stats['total_penduduk'] ?? 0) }}</div>
        <div class="text-sm font-medium opacity-80">Total Penduduk</div>
      </div>
      <div class="reveal">
        <div class="text-4xl mb-3 opacity-90">👶</div>
        <div class="text-5xl font-black mb-2" style="font-family: 'Inter', sans-serif; color: #86efac;">{{ number_format($stats['balita'] ?? 0) }}</div>
        <div class="text-sm font-medium opacity-80">Bayi & Balita</div>
      </div>
      <div class="reveal">
        <div class="text-4xl mb-3 opacity-90">🤰</div>
        <div class="text-5xl font-black mb-2" style="font-family: 'Inter', sans-serif; color: #fda4af;">{{ number_format($stats['ibu_hamil'] ?? 0) }}</div>
        <div class="text-sm font-medium opacity-80">Ibu Hamil Aktif</div>
      </div>
      <div class="reveal">
        <div class="text-4xl mb-3 opacity-90">🧓</div>
        <div class="text-5xl font-black mb-2" style="font-family: 'Inter', sans-serif; color: #93c5fd;">{{ number_format($stats['lansia'] ?? 0) }}</div>
        <div class="text-sm font-medium opacity-80">Lanjut Usia (Lansia)</div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== UNIT POSYANDU & FASILITAS ===================== -->
<section id="unit-posyandu" class="py-24 relative overflow-hidden" style="background: white;">
  <div class="absolute top-0 left-0 w-80 h-80 rounded-full opacity-5 floating-slow" style="background: var(--teal); transform: translate(-30%, -30%);"></div>
  <div class="absolute bottom-0 right-0 w-64 h-64 rounded-full opacity-5 floating-rev" style="background: var(--mint); transform: translate(30%, 30%);"></div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="text-center mb-16 reveal">
      <div class="section-badge">🏡 Unit Posyandu Kami</div>
      <h2 class="text-4xl sm:text-5xl font-black mb-4" style="color: var(--deep);">
        Profil & <span class="gradient-text">Fasilitas Posyandu</span>
      </h2>
      <p class="text-lg max-w-xl mx-auto" style="color: rgba(2,52,48,0.6);">
        Berikut data sebaran unit pelayanan Posyandu aktif beserta ketersediaan fasilitas penunjang di masing-masing wilayah.
      </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
      @foreach($posyandus as $posyandu)
        <div class="reveal bg-white rounded-3xl border border-gray-100 p-8 shadow-md hover:shadow-xl transition-all duration-300 relative flex flex-col justify-between" style="border-color: rgba(62,207,142,0.15);">
          <!-- Top Section -->
          <div>
            <div class="flex items-center justify-between mb-6">
              <div class="p-3.5 rounded-2xl flex items-center justify-center text-3xl" style="background: rgba(62,207,142,0.1); color: var(--teal);">
                🏢
              </div>
              <span class="text-xs px-3.5 py-1.5 rounded-full font-bold uppercase tracking-wider" style="background: rgba(15,154,123,0.1); color: var(--teal);">
                Dusun {{ $posyandu->dusun }}
              </span>
            </div>

            <h3 class="text-2xl font-black mb-2" style="color: var(--deep);">{{ $posyandu->nama }}</h3>
            <p class="text-xs font-semibold mb-4" style="color: rgba(2,52,48,0.5);">
              Ketua: <span style="color: var(--teal);">{{ $posyandu->ketua ?? '-' }}</span>
            </p>

            <div class="space-y-3.5 mb-6">
              <div class="flex items-center gap-2.5 text-sm" style="color: rgba(2,52,48,0.75);">
                <span class="text-gray-400">👥</span>
                <span><strong>{{ $posyandu->jumlah_kader ?? 0 }}</strong> Kader Aktif</span>
              </div>
              <div class="flex items-center gap-2.5 text-sm" style="color: rgba(2,52,48,0.75);">
                <span class="text-gray-400">📍</span>
                <span class="line-clamp-1">{{ $posyandu->alamat ?? '-' }}</span>
              </div>
              @if(is_array($posyandu->rw_diampu) && count($posyandu->rw_diampu) > 0)
                <div class="flex items-start gap-2.5 text-sm" style="color: rgba(2,52,48,0.75);">
                  <span class="text-gray-400">🗺️</span>
                  <span>Mengampu RW: 
                    <span class="font-semibold text-gray-800">
                      {{ implode(', ', array_map(fn($rw) => 'RW ' . sprintf('%02d', $rw), $posyandu->rw_diampu)) }}
                    </span>
                  </span>
                </div>
              @endif
            </div>

            <!-- Facilities -->
            <div class="border-t pt-5" style="border-color: rgba(62,207,142,0.1);">
              <h4 class="text-xs font-bold uppercase tracking-wider mb-3" style="color: rgba(2,52,48,0.65);">Ketersediaan Alat & KIA</h4>
              <div class="grid grid-cols-2 gap-2 text-xs">
                <div class="flex items-center gap-2">
                  <span class="{{ $posyandu->punya_timbangan_digital ? 'text-green-500' : 'text-gray-300' }}">●</span>
                  <span class="{{ $posyandu->punya_timbangan_digital ? 'font-semibold text-gray-700' : 'text-gray-400 line-through' }}">Timbangan Digital</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="{{ $posyandu->punya_timbangan_dacin ? 'text-green-500' : 'text-gray-300' }}">●</span>
                  <span class="{{ $posyandu->punya_timbangan_dacin ? 'font-semibold text-gray-700' : 'text-gray-400 line-through' }}">Timbangan Dacin</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="{{ $posyandu->punya_alat_ukur_tinggi ? 'text-green-500' : 'text-gray-300' }}">●</span>
                  <span class="{{ $posyandu->punya_alat_ukur_tinggi ? 'font-semibold text-gray-700' : 'text-gray-400 line-through' }}">Alat Ukur Tinggi</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="{{ $posyandu->punya_pita_liLa ? 'text-green-500' : 'text-gray-300' }}">●</span>
                  <span class="{{ $posyandu->punya_pita_liLa ? 'font-semibold text-gray-700' : 'text-gray-400 line-through' }}">Pita LiLa</span>
                </div>
                <div class="flex items-center gap-2 col-span-2">
                  <span class="{{ $posyandu->punya_buku_kia ? 'text-green-500' : 'text-gray-300' }}">●</span>
                  <span class="{{ $posyandu->punya_buku_kia ? 'font-semibold text-gray-700' : 'text-gray-400 line-through' }}">Buku KIA (Kartu Ibu & Anak)</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Bottom Action/Jadwal -->
          @if($posyandu->jadwal_rutin)
            <div class="mt-6 pt-4 border-t flex items-center justify-between text-xs" style="border-color: rgba(62,207,142,0.1); color: var(--teal);">
              <span class="font-bold">📅 Jadwal Rutin:</span>
              <span class="font-semibold text-gray-700">{{ $posyandu->jadwal_rutin }}</span>
            </div>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</section>


<!-- ===================== JADWAL ===================== -->
<section id="jadwal" class="py-24 relative overflow-hidden" style="background: var(--cream);">
  <div class="absolute inset-0 dot-pattern opacity-30 pointer-events-none"></div>
  <!-- Floating elements -->
  <div class="absolute top-20 right-10 w-24 h-24 blob2 opacity-5 floating" style="background: var(--teal);"></div>
  <div class="absolute bottom-20 left-5 w-32 h-32 blob1 opacity-5 floating-slow" style="background: var(--mint);"></div>
  
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

    <div class="grid lg:grid-cols-2 gap-16 items-start">
      <!-- Left -->
      <div class="reveal">
        <div class="section-badge">📅 Jadwal Pelayanan</div>
        <h2 class="text-4xl sm:text-5xl font-black mb-4" style="color: var(--deep);">
          Kapan Kami<br/><span class="gradient-text">Siap Melayani</span>
        </h2>
        <p class="text-lg mb-8" style="color: rgba(2,52,48,0.65);">
          Posyandu kami melayani masyarakat setiap bulan dengan jadwal yang teratur dan terstruktur.
        </p>

        <!-- Schedule table -->
        <div class="rounded-3xl overflow-hidden border shadow-lg" style="border-color: rgba(62,207,142,0.2); background: white;">
          <div class="px-6 py-4" style="background: linear-gradient(135deg, var(--teal), var(--mint));">
            <div class="grid grid-cols-3 text-sm font-bold text-white">
              <div>Hari / Tanggal</div>
              <div>Jam</div>
              <div>Kegiatan</div>
            </div>
          </div>
          <div class="divide-y" style="border-color: rgba(62,207,142,0.1);">
            @forelse($jadwals as $jadwal)
              <div class="grid grid-cols-3 px-6 py-4 text-sm schedule-row transition-colors">
                <div class="font-semibold" style="color: var(--deep);">{{ $jadwal->hari_tanggal }}</div>
                <div style="color: var(--teal);">{{ $jadwal->jam_mulai }}{{ $jadwal->jam_selesai ? '–' . $jadwal->jam_selesai : '' }}</div>
                <div style="color: rgba(2,52,48,0.7);">
                  {{ $jadwal->kegiatan }}
                  @if($jadwal->keterangan)
                    <div class="text-xs mt-0.5 opacity-70">{{ $jadwal->keterangan }}</div>
                  @endif
                </div>
              </div>
            @empty
              <div class="px-6 py-8 text-center text-sm" style="color: rgba(2,52,48,0.6);">
                Belum ada jadwal pelayanan yang diterbitkan.
              </div>
            @endforelse
          </div>
        </div>

        <div class="mt-6 rounded-2xl p-4 flex items-start gap-3" style="background: rgba(62,207,142,0.1); border: 1px solid rgba(62,207,142,0.2);">
          <span class="text-xl mt-0.5">📌</span>
          <p class="text-sm" style="color: rgba(2,52,48,0.75);">
            <strong>Catatan:</strong> Jadwal sewaktu-waktu dapat berubah. Ikuti pengumuman di papan RW atau hubungi kader setempat untuk konfirmasi jadwal terbaru.
          </p>
        </div>
      </div>

      <!-- Right: Info cards -->
      <div class="space-y-5 reveal">
        <div class="rounded-3xl p-6 border card-hover" style="background: white; border-color: rgba(62,207,142,0.2);">
         <div class="flex items-start gap-4">
           <div class="icon-circle text-2xl" style="background: rgba(62,207,142,0.12);">📍</div>
           <div>
             <h3 class="font-bold text-lg mb-1" style="color: var(--deep);">Lokasi Posyandu</h3>
             <p class="text-sm" style="color: rgba(2,52,48,0.65);">
                {{ $settings['alamat_desa'] ?? 'Jl. Raya Banjar No. 1, Desa Banjar' }}
             </p>
           </div>
         </div>
        </div>
        <div class="rounded-3xl p-6 border card-hover" style="background: white; border-color: rgba(62,207,142,0.2);">
          <div class="flex items-start gap-4">
            <div class="icon-circle text-2xl" style="background: rgba(245,200,66,0.12);">📋</div>
            <div>
              <h3 class="font-bold text-lg mb-2" style="color: var(--deep);">Yang Perlu Dibawa</h3>
              <ul class="text-sm space-y-1.5" style="color: rgba(2,52,48,0.65);">
                <li class="flex items-center gap-2"><span style="color: var(--teal);">✓</span> KTP / KK orang tua</li>
                <li class="flex items-center gap-2"><span style="color: var(--teal);">✓</span> Buku KIA / KMS anak</li>
                <li class="flex items-center gap-2"><span style="color: var(--teal);">✓</span> Kartu imunisasi (bila ada)</li>
                <li class="flex items-center gap-2"><span style="color: var(--teal);">✓</span> Buku kesehatan lansia</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="rounded-3xl p-6 border card-hover" style="background: white; border-color: rgba(62,207,142,0.2);">
          <div class="flex items-start gap-4">
            <div class="icon-circle text-2xl" style="background: rgba(59,130,246,0.1);">📞</div>
            <div>
              <h3 class="font-bold text-lg mb-1" style="color: var(--deep);">Hubungi Kader</h3>
              <p class="text-sm mb-3" style="color: rgba(2,52,48,0.65);">Untuk informasi jadwal atau pendaftaran awal:</p>
              <div class="flex flex-col gap-1.5">
                <a href="https://wa.me/{{ $settings['no_whatsapp'] ?? '6285123456789' }}" class="text-sm font-semibold flex items-center gap-2" style="color: var(--teal);">
                  📱 +{{ $settings['no_whatsapp'] ?? '62 851-2345-6789' }}
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Mini health tip card -->
        <div class="rounded-3xl p-6 relative overflow-hidden" style="background: linear-gradient(135deg, var(--teal), var(--mint));">
          <div class="absolute -right-4 -bottom-4 text-8xl opacity-20">🌿</div>
          <h3 class="font-bold text-lg text-white mb-2">Tips Kesehatan</h3>
          <p class="text-sm text-white opacity-85 leading-relaxed">
            Pastikan balita Anda datang ke posyandu setiap bulan untuk pemantauan pertumbuhan yang optimal dan deteksi dini masalah kesehatan.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ===================== TIM ===================== -->
<section id="tim" class="py-24 bg-white relative overflow-hidden">
  <div class="absolute top-0 right-0 w-72 h-72 opacity-5 blob1 floating-slow" style="background: var(--teal); transform: translate(20%, -20%);"></div>
  <div class="absolute bottom-10 left-10 w-40 h-40 opacity-5 blob2 floating-rev" style="background: var(--gold);"></div>
  <div class="absolute top-1/2 left-5 text-4xl opacity-10 floating" style="color: var(--teal);">♥️</div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-16 reveal">
      <div class="section-badge">👩‍⚕️ Tim Kami</div>
      <h2 class="text-4xl sm:text-5xl font-black mb-4" style="color: var(--deep);">
        Orang-Orang di Balik<br/><span class="gradient-text">{{ $settings['nama_aplikasi'] ?? 'Posyandu Banjar' }}</span>
      </h2>
      <p class="text-lg max-w-lg mx-auto" style="color: rgba(2,52,48,0.6);">
        Tim kader dan tenaga kesehatan kami yang berdedikasi, berpengalaman, dan penuh kasih.
      </p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">

      @php
        $gradients = [
          'linear-gradient(135deg, #d1fae5, #a7f3d0)',
          'linear-gradient(135deg, #fce7f3, #fbcfe8)',
          'linear-gradient(135deg, #fef3c7, #fde68a)',
          'linear-gradient(135deg, #ede9fe, #ddd6fe)',
        ];
        $emojis = ['👩‍⚕️', '👩‍🍼', '🧑‍⚕️', '👩‍💼', '👨‍⚕️', '👩‍🔬'];
        $textColors = ['var(--teal)', '#db2777', '#d97706', '#7c3aed'];
      @endphp

      @forelse($tims as $index => $tim)
        @php
          $bgGrad = $gradients[$index % count($gradients)];
          $textColor = $textColors[$index % count($textColors)];
          $emoji = $emojis[$index % count($emojis)];
        @endphp
        <div class="reveal card-hover rounded-3xl overflow-hidden border text-center" style="border-color: rgba(62,207,142,0.15); background: var(--cream);">
          <div class="h-36 relative" style="background: {{ $bgGrad }};">
            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 w-20 h-20 rounded-full flex items-center justify-center text-4xl shadow-lg bg-white overflow-hidden" style="border: 4px solid white;">
              @if($tim->foto)
                <img src="{{ asset($tim->foto) }}" alt="{{ $tim->nama }}" class="w-full h-full object-cover">
              @else
                {{ $emoji }}
              @endif
            </div>
          </div>
          <div class="pt-12 pb-6 px-4">
            <h3 class="font-bold text-base mb-0.5 line-clamp-1" style="color: var(--deep);">{{ $tim->nama }}</h3>
            <p class="text-sm mb-3 line-clamp-1" style="color: {{ $textColor }};">{{ $tim->jabatan }}</p>
            <p class="text-xs leading-relaxed line-clamp-3" style="color: rgba(2,52,48,0.6);">{{ $tim->deskripsi ?? '-' }}</p>
          </div>
        </div>
      @empty
        <div class="col-span-full text-center py-12 text-gray-500 text-sm">
          Belum ada profil anggota tim yang ditambahkan.
        </div>
      @endforelse

    </div>
  </div>
</section>


@endsection