@extends('layouts.public')

@section('title', 'Berita')

@section('content')
<section class="hero-bg relative min-h-[40vh] flex items-center pt-28 pb-16 overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
    <div class="inline-flex items-center justify-center gap-2 section-badge floating" style="margin-bottom: 1.5rem;">
      <span style="color: var(--teal);">Berita & Pengumuman</span>
    </div>
    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-tight mb-6" style="color: var(--deep);">
      Warta <span style="background: linear-gradient(135deg, var(--teal), var(--mint)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Kesehatan</span>
    </h1>
    <p class="text-lg md:text-xl max-w-2xl mx-auto font-medium" style="color: rgba(2,52,48,0.7);">
      Jendela informasi seputar kegiatan Posyandu, edukasi pola hidup sehat, dan pengumuman penting bagi warga desa.
    </p>

    <!-- Search Form -->
    <div class="mt-10 max-w-xl mx-auto">
      <form action="{{ route('public.news.index') }}" method="GET" class="relative group">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari artikel atau berita..." 
               class="w-full pl-14 pr-6 py-4 bg-white rounded-[2rem] shadow-xl border border-transparent focus:outline-none transition-all text-sm font-medium"
               style="color: var(--deep); box-shadow: 0 10px 30px rgba(15,154,123,0.08);">
        <div class="absolute inset-y-0 left-6 flex items-center transition-colors" style="color: var(--teal);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
        </div>
      </form>
    </div>
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      @forelse($beritas as $index => $news)
      <article class="bg-white rounded-3xl overflow-hidden border card-hover flex flex-col h-full" style="border-color: rgba(62,207,142,0.15);">
        <div class="relative h-64 overflow-hidden">
          @if($news->gambar)
            <img src="{{ Str::startsWith($news->gambar, ['http://', 'https://']) ? $news->gambar : asset('storage/' . $news->gambar) }}" alt="{{ $news->judul }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000">
          @else
            <div class="w-full h-full flex items-center justify-center group-hover:scale-110 transition-transform duration-1000" style="background: rgba(62,207,142,0.05);">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="color: rgba(15,154,123,0.3);">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                <polyline points="21 15 16 10 5 21"></polyline>
              </svg>
            </div>
          @endif
          <div class="absolute top-4 left-4 flex flex-wrap gap-2">
            <div class="px-3 py-1.5 bg-white/90 backdrop-blur-sm rounded-xl text-xs font-bold uppercase tracking-wider" style="color: var(--teal); box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
              {{ $news->kategori ?? 'Kegiatan' }}
            </div>
            <div class="px-3 py-1.5 bg-teal-800/85 text-white backdrop-blur-sm rounded-xl text-xs font-bold tracking-wider shadow-sm flex items-center gap-1.5">
              <i class="mdi mdi-account-circle"></i>
              <span>{{ $news->penulis_nama }}</span>
            </div>
          </div>
        </div>
        <div class="p-8 flex flex-col flex-1">
          <div class="flex items-center gap-3 text-xs font-bold uppercase tracking-widest mb-4" style="color: rgba(2,52,48,0.5);">
            <span>{{ $news->created_at->format('d M Y') }}</span>
            <span class="w-1 h-1 rounded-full" style="background: var(--mint);"></span>
            <span>{{ ceil(str_word_count(strip_tags($news->konten)) / 200) }} Min Baca</span>
          </div>
          <h2 class="text-xl font-bold leading-tight mb-3 line-clamp-2 transition-colors" style="color: var(--deep);">
            <a href="{{ route('public.news.show', $news->id) }}" class="hover:text-teal-600" style="text-decoration-color: var(--teal);">{{ $news->judul }}</a>
          </h2>
          <p class="text-sm leading-relaxed font-medium flex-1 line-clamp-3 mb-6" style="color: rgba(2,52,48,0.65);">
            {{ strip_tags($news->konten) }}
          </p>
          <div class="pt-5 mt-auto border-t" style="border-color: rgba(62,207,142,0.1);">
            <a href="{{ route('public.news.show', $news->id) }}" class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest transition-all hover:gap-3" style="color: var(--teal);">
              Selengkapnya
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"></path>
                <path d="M12 5l7 7-7 7"></path>
              </svg>
            </a>
          </div>
        </div>
      </article>
      @empty
      <div class="col-span-full py-20 text-center">
        <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6" style="background: rgba(62,207,142,0.1); color: var(--teal);">
          <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
          </svg>
        </div>
        <h3 class="text-2xl font-bold mb-3" style="color: var(--deep);">Warta Tidak Ditemukan</h3>
        <p class="text-sm font-medium mb-8" style="color: rgba(2,52,48,0.6);">Coba gunakan kata kunci pencarian yang berbeda.</p>
        <a href="{{ route('public.news.index') }}" class="btn-outline">Lihat Semua Berita</a>
      </div>
      @endforelse
    </div>

    <div class="mt-16 flex justify-center">
      {{ $beritas->links() }}
    </div>
  </div>
</section>
@endsection