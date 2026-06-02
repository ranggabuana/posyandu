<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title') | {{ $settings['nama_aplikasi'] ?? 'Posyandu Melati Sehat' }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
  <style>
    :root {
      --mint: #3ecf8e;
      --mint-light: #d1fae5;
      --teal: #0f9a7b;
      --deep: #023430;
      --cream: #f8fdf9;
      --warm: #fffbf0;
      --coral: #ff6b6b;
      --gold: #f5c842;
    }

    * { scroll-behavior: smooth; }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--cream);
      color: var(--deep);
      overflow-x: hidden;
    }

    h1, h2, h3 {
      font-family: 'Inter', sans-serif;
    }

    /* Animated gradient mesh bg */
    .hero-bg {
      background: radial-gradient(ellipse at 20% 50%, rgba(62,207,142,0.18) 0%, transparent 60%),
                  radial-gradient(ellipse at 80% 20%, rgba(15,154,123,0.15) 0%, transparent 60%),
                  radial-gradient(ellipse at 60% 80%, rgba(245,200,66,0.1) 0%, transparent 50%),
                  linear-gradient(135deg, #f0fdf7 0%, #ecfdf5 50%, #f8fdf9 100%);
    }

    .floating {
      animation: float 6s ease-in-out infinite;
    }
    .floating-slow {
      animation: float 9s ease-in-out infinite;
    }
    .floating-rev {
      animation: floatRev 7s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-18px); }
    }
    @keyframes floatRev {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(14px); }
    }

    .pulse-ring {
      animation: pulseRing 2.5s ease-out infinite;
    }
    @keyframes pulseRing {
      0% { transform: scale(1); opacity: 0.6; }
      100% { transform: scale(1.7); opacity: 0; }
    }

    /* Blob shapes */
    .blob1 {
      border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
    }
    .blob2 {
      border-radius: 70% 30% 30% 70% / 70% 70% 30% 30%;
    }

    .card-hover {
      transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .card-hover:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 24px 60px rgba(15,154,123,0.18);
    }

    .nav-link {
      position: relative;
    }
    .nav-link::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--mint);
      transition: width 0.3s ease;
    }
    .nav-link:hover::after { width: 100%; }

    /* Decorative cross/plus */
    .health-cross {
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .health-cross::before,
    .health-cross::after {
      content: '';
      position: absolute;
      background: currentColor;
    }
    .health-cross::before {
      width: 60%;
      height: 100%;
    }
    .health-cross::after {
      width: 100%;
      height: 60%;
    }

    /* Pattern dots */
    .dot-pattern {
      background-image: radial-gradient(circle, rgba(62,207,142,0.3) 1.5px, transparent 1.5px);
      background-size: 22px 22px;
    }

    /* Wavy section divider */
    .wave-top {
      position: absolute;
      top: -1px;
      left: 0;
      width: 100%;
      overflow: hidden;
      line-height: 0;
    }

    .stat-num {
      font-family: 'Inter', sans-serif;
      font-weight: 900;
      font-size: clamp(2rem, 5vw, 3.5rem);
      line-height: 1;
      background: linear-gradient(135deg, var(--teal), var(--mint));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .section-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(62,207,142,0.12);
      border: 1px solid rgba(62,207,142,0.3);
      border-radius: 100px;
      padding: 6px 18px;
      font-size: 13px;
      font-weight: 600;
      color: var(--teal);
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 1.2rem;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--teal) 0%, var(--mint) 100%);
      color: white;
      padding: 14px 32px;
      border-radius: 100px;
      font-weight: 600;
      font-size: 15px;
      transition: all 0.3s ease;
      box-shadow: 0 8px 24px rgba(15,154,123,0.35);
      letter-spacing: 0.02em;
    }
    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 14px 36px rgba(15,154,123,0.45);
    }

    .btn-outline {
      background: transparent;
      color: var(--teal);
      border: 2px solid var(--teal);
      padding: 12px 30px;
      border-radius: 100px;
      font-weight: 600;
      font-size: 15px;
      transition: all 0.3s ease;
    }
    .btn-outline:hover {
      background: var(--teal);
      color: white;
      transform: translateY(-3px);
    }

    /* Staggered animations on load */
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .anim-1 { animation: slideUp 0.7s ease forwards; animation-delay: 0.1s; opacity: 0; }
    .anim-2 { animation: slideUp 0.7s ease forwards; animation-delay: 0.3s; opacity: 0; }
    .anim-3 { animation: slideUp 0.7s ease forwards; animation-delay: 0.5s; opacity: 0; }
    .anim-4 { animation: slideUp 0.7s ease forwards; animation-delay: 0.7s; opacity: 0; }
    .anim-5 { animation: slideUp 0.7s ease forwards; animation-delay: 0.9s; opacity: 0; }

    /* Scroll reveal */
    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.7s ease, transform 0.7s ease;
    }
    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* Testimonial card */
    .testimonial-card {
      background: white;
      border-radius: 24px;
      padding: 32px;
      box-shadow: 0 4px 24px rgba(0,0,0,0.06);
      border: 1px solid rgba(62,207,142,0.12);
      position: relative;
    }
    .testimonial-card::before {
      content: '"';
      font-family: 'Inter', sans-serif;
      font-size: 80px;
      line-height: 1;
      color: rgba(62,207,142,0.2);
      position: absolute;
      top: 12px;
      left: 24px;
    }

    /* Schedule table */
    .schedule-row:hover {
      background: rgba(62,207,142,0.07);
    }

    /* Gradient text */
    .gradient-text {
      background: linear-gradient(135deg, var(--deep) 0%, var(--teal) 60%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* Icon circle */
    .icon-circle {
      width: 56px;
      height: 56px;
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 26px;
      flex-shrink: 0;
    }

    /* Scrollbar */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f0fdf7; }
    ::-webkit-scrollbar-thumb { background: var(--mint); border-radius: 3px; }

    /* Mobile menu */
    #mobile-menu {
      transition: all 0.3s ease;
    }

    /* Hero illustration */
    .hero-illustration {
      position: relative;
    }

    /* Green circle decoration */
    .deco-circle {
      position: absolute;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(62,207,142,0.15) 0%, transparent 70%);
    }
  </style>
  @yield('styles')
</head>
<body class="antialiased">

<!-- ===================== NAVBAR ===================== -->
<nav class="fixed top-0 left-0 right-0 z-50 backdrop-blur-md bg-white/85 border-b border-mint-100" style="border-color: rgba(62,207,142,0.15);">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <!-- Logo -->
      <div class="flex items-center gap-3">
        @if (!empty($settings['logo_desa']))
          <img src="{{ Str::startsWith($settings['logo_desa'], ['http://', 'https://']) ? $settings['logo_desa'] : asset('storage/' . $settings['logo_desa']) }}" 
               alt="Logo Desa" class="w-9 h-9 object-contain rounded-xl">
        @else
          <div class="relative w-9 h-9 flex items-center justify-center">
            <div class="absolute inset-0 rounded-xl" style="background: linear-gradient(135deg, #0f9a7b, #3ecf8e);"></div>
            <!-- Plus/cross icon -->
            <svg class="relative z-10" width="20" height="20" viewBox="0 0 20 20" fill="none">
              <rect x="8" y="2" width="4" height="16" rx="2" fill="white"/>
              <rect x="2" y="8" width="16" height="4" rx="2" fill="white"/>
            </svg>
          </div>
        @endif
        <div>
          <div class="font-bold text-base leading-tight" style="color: var(--deep); font-family: 'Inter', sans-serif;">{{ $settings['nama_aplikasi'] ?? 'Posyandu' }}</div>
          <div class="text-xs font-medium" style="color: var(--teal); letter-spacing: 0.05em;">{{ $settings['nama_desa'] ?? 'MELATI SEHAT' }}</div>
        </div>
      </div>

      <!-- Desktop nav -->
      <div class="hidden md:flex items-center gap-8">
        <a href="{{ route('landing') }}" class="nav-link text-sm font-medium" style="color: var(--deep);">Beranda</a>
        <a href="{{ route('public.news.index') }}" class="nav-link text-sm font-medium" style="color: var(--deep);">Berita</a>
        <a href="{{ route('public.gallery.index') }}" class="nav-link text-sm font-medium" style="color: var(--deep);">Galeri</a>
        <a href="{{ route('public.aduan') }}" class="nav-link text-sm font-medium" style="color: var(--deep);">Aduan</a>
      </div>

      <!-- CTA + hamburger -->
      <div class="flex items-center gap-3">
        <a href="{{ route('login') }}" class="hidden sm:block btn-primary text-sm py-2 px-5">Login</a>
        <button id="menu-btn" class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg" style="background: rgba(62,207,142,0.1);">
          <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <path d="M2 4h14M2 9h14M2 14h14" style="stroke: var(--teal);"/>
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile menu -->
  <div id="mobile-menu" class="hidden md:hidden px-4 pb-4">
    <div class="rounded-2xl p-4 space-y-1" style="background: var(--mint-light);">
      <a href="{{ route('landing') }}" class="block px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-white transition" style="color: var(--deep);">Beranda</a>
      <a href="{{ route('public.news.index') }}" class="block px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-white transition" style="color: var(--deep);">Berita</a>
      <a href="{{ route('public.gallery.index') }}" class="block px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-white transition" style="color: var(--deep);">Galeri</a>
      <a href="{{ route('public.aduan') }}" class="block px-4 py-2.5 text-sm font-medium rounded-xl hover:bg-white transition" style="color: var(--deep);">Aduan</a>
      <div class="pt-2">
        <a href="{{ route('login') }}" class="block btn-primary text-center text-sm py-2.5">Login</a>
      </div>
    </div>
  </div>
</nav>

<main class="min-h-screen">
    @yield('content')
</main>

<!-- ===================== FOOTER ===================== -->
<footer class="py-12 border-t" style="background: var(--deep); border-color: rgba(62,207,142,0.15);">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid sm:grid-cols-3 gap-10 mb-10">

      <!-- Brand -->
      <div>
        <div class="flex items-center gap-3 mb-4">
          <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #0f9a7b, #3ecf8e);">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
              <rect x="8" y="2" width="4" height="16" rx="2" fill="white"/>
              <rect x="2" y="8" width="16" height="4" rx="2" fill="white"/>
            </svg>
          </div>
          <div>
            <div class="font-bold text-sm text-white" style="font-family: 'Inter', sans-serif;">{{ $settings['nama_aplikasi'] ?? 'Posyandu Melati Sehat' }}</div>
            <div class="text-xs" style="color: rgba(255,255,255,0.4);">{{ $settings['alamat_desa'] ?? 'Jl. Raya Banjar No. 1' }}</div>
          </div>
        </div>
        <p class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.55);">
          {{ $settings['moto'] ?? 'Melayani dengan hati, menjaga kesehatan komunitas sejak 2012.' }}
        </p>
      </div>

      <!-- Quick links -->
      <div>
        <h4 class="text-sm font-bold text-white mb-4">Tautan Cepat</h4>
        <ul class="space-y-2">
          <li><a href="{{ route('landing') }}" class="text-sm hover:text-white transition" style="color: rgba(255,255,255,0.55);">Beranda</a></li>
          <li><a href="{{ route('public.news.index') }}" class="text-sm hover:text-white transition" style="color: rgba(255,255,255,0.55);">Warta Kesehatan</a></li>
          <li><a href="{{ route('public.gallery.index') }}" class="text-sm hover:text-white transition" style="color: rgba(255,255,255,0.55);">Galeri Momen</a></li>
          <li><a href="{{ route('public.aduan') }}" class="text-sm hover:text-white transition" style="color: rgba(255,255,255,0.55);">Layanan & Aduan</a></li>
        </ul>
      </div>

      <!-- Info -->
      <div>
        <h4 class="text-sm font-bold text-white mb-4">Jam Operasional</h4>
        @php
            $jamOps = explode("\n", $settings['jam_operasional'] ?? "Senin – Jumat: 07:30 – 12:00\nSabtu: 07:30 – 11:00\nMinggu & Libur: Tutup");
        @endphp
        <ul class="space-y-2 text-sm" style="color: rgba(255,255,255,0.55);">
          @foreach($jamOps as $jam)
            @if(trim($jam) !== '')
              <li>{{ trim($jam) }}</li>
            @endif
          @endforeach
          <li class="mt-3 text-xs" style="color: rgba(255,255,255,0.4);">*Sesuai jadwal kegiatan bulanan</li>
        </ul>
      </div>
    </div>

    <div class="pt-6 border-t flex flex-col sm:flex-row items-center justify-between gap-3" style="border-color: rgba(255,255,255,0.1);">
      <p class="text-xs" style="color: rgba(255,255,255,0.35);">© {{ date('Y') }} {{ $settings['nama_aplikasi'] ?? 'Posyandu Melati Sehat' }}. Hak cipta dilindungi.</p>
      <div class="flex items-center gap-1 text-xs" style="color: rgba(255,255,255,0.35);">
        <span>Dibuat dengan</span>
        <span style="color: #ff6b6b;">❤️</span>
        <span>untuk masyarakat Indonesia</span>
      </div>
    </div>
  </div>
</footer>

<!-- Back to Top Button -->
<button id="back-to-top" class="fixed bottom-6 right-6 z-50 w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-xl cursor-pointer hover:scale-110 active:scale-95 transition-all opacity-0 pointer-events-none" style="background: linear-gradient(135deg, var(--teal), var(--mint)); box-shadow: 0 8px 24px rgba(15,154,123,0.3);" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Kembali ke Atas">
  <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
    <path d="M18 15l-6-6-6 6"/>
  </svg>
</button>

<!-- ===================== SCRIPTS ===================== -->
<script>
  // Mobile menu toggle
  const menuBtn = document.getElementById('menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  if(menuBtn && mobileMenu) {
      menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
      });
      // Close on link click
      mobileMenu.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => mobileMenu.classList.add('hidden'));
      });
  }

  // Scroll reveal
  const reveals = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry, i) => {
      if (entry.isIntersecting) {
        entry.target.style.transitionDelay = (i % 6) * 80 + 'ms';
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });
  reveals.forEach(el => observer.observe(el));

  // Navbar scroll effect
  const nav = document.querySelector('nav');
  const backToTopBtn = document.getElementById('back-to-top');
  
  window.addEventListener('scroll', () => {
    // Navbar logic
    if (window.scrollY > 50) {
      if(nav) {
          nav.style.background = 'rgba(255,255,255,0.95)';
          nav.style.boxShadow = '0 4px 24px rgba(0,0,0,0.06)';
      }
    } else {
      if(nav) {
          nav.style.background = 'rgba(255,255,255,0.85)';
          nav.style.boxShadow = 'none';
      }
    }

    // Back to top button logic
    if (window.scrollY > 300) {
      if(backToTopBtn) {
          backToTopBtn.classList.remove('opacity-0', 'pointer-events-none');
          backToTopBtn.classList.add('opacity-100', 'pointer-events-auto');
      }
    } else {
      if(backToTopBtn) {
          backToTopBtn.classList.add('opacity-0', 'pointer-events-none');
          backToTopBtn.classList.remove('opacity-100', 'pointer-events-auto');
      }
    }
  });

  // Form submit interactive
  function handleSubmitInteractive(e) {
    e.preventDefault();
    const form = e.target;
    const loading = document.getElementById('loadingState');
    const success = document.getElementById('successMessage');
    
    // Show loading
    if(loading) loading.classList.remove('hidden');
    
    // Simulate API call
    setTimeout(() => {
      if(loading) loading.classList.add('hidden');
      form.style.display = 'none';
      if(success) {
          success.classList.remove('hidden');
          success.style.animation = 'slideUp 0.5s ease forwards';
      }
    }, 1500);
  }

  // Reset form
  function resetForm() {
    const form = document.getElementById('daftarForm');
    const success = document.getElementById('successMessage');
    
    if(form) form.reset();
    if(success) success.classList.add('hidden');
    if(form) {
        form.style.display = 'block';
        form.style.animation = 'slideUp 0.5s ease forwards';
    }
  }

  // Input focus glow
  document.querySelectorAll('input, select, textarea').forEach(el => {
    el.addEventListener('focus', () => {
      el.style.borderColor = 'rgba(62,207,142,0.6)';
      el.style.background = 'rgba(255,255,255,0.18)';
    });
    el.addEventListener('blur', () => {
      el.style.borderColor = 'rgba(255,255,255,0.2)';
      el.style.background = 'rgba(255,255,255,0.12)';
    });
  });

  // Input placeholder color (CSS injection)
  const style = document.createElement('style');
  style.textContent = `
    input::placeholder, textarea::placeholder { color: rgba(255,255,255,0.45) !important; }
    select option { background: #023430 !important; color: white !important; }
    .schedule-row:hover { background: rgba(62,207,142,0.07); cursor: default; }
  `;
  document.head.appendChild(style);
</script>
@yield('scripts')
</body>
</html>