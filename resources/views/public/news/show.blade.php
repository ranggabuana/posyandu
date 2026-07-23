@extends('layouts.public')

@section('title', $berita->judul)

@section('content')
<section class="py-32" style="background-color: var(--cream);">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <header class="mb-12">
      <a href="{{ route('public.news.index') }}" class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest hover:gap-3 transition-all mb-8" style="color: var(--teal);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M19 12H5"></path>
          <path d="M12 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Berita
      </a>
      
      <div class="flex flex-wrap items-center gap-3 text-xs font-bold uppercase tracking-widest mb-6" style="color: rgba(2,52,48,0.5);">
        <span class="px-4 py-2 rounded-full" style="background: rgba(62,207,142,0.15); color: var(--teal);">
          {{ $berita->kategori ?? 'Kegiatan' }}
        </span>
        <span class="px-4 py-2 rounded-full bg-teal-800/10 text-teal-800 flex items-center gap-1.5 font-bold">
          <i class="mdi mdi-account-circle text-sm"></i>
          <span>Sumber: {{ $berita->penulis_nama }}</span>
        </span>
        <span>•</span>
        <span>{{ $berita->created_at->format('d F Y') }}</span>
      </div>
      
      <h1 class="text-3xl md:text-5xl font-black leading-tight" style="color: var(--deep);">
        {{ $berita->judul }}
      </h1>
    </header>

    <!-- Featured Image -->
    @if($berita->gambar)
    <div class="rounded-[2.5rem] overflow-hidden shadow-2xl mb-16" style="box-shadow: 0 24px 60px rgba(15,154,123,0.15);">
      <img src="{{ Str::startsWith($berita->gambar, ['http://', 'https://']) ? $berita->gambar : asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}" class="w-full h-auto object-cover max-h-[600px]">
    </div>
    @else
    <div class="rounded-[2.5rem] overflow-hidden shadow-2xl mb-16 flex items-center justify-center" style="background: rgba(62,207,142,0.05); box-shadow: 0 24px 60px rgba(15,154,123,0.15); height: 400px;">
      <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="color: rgba(15,154,123,0.2);">
        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
        <circle cx="8.5" cy="8.5" r="1.5"></circle>
        <polyline points="21 15 16 10 5 21"></polyline>
      </svg>
    </div>
    @endif

    <!-- Content -->
    <div class="prose prose-lg max-w-none" style="color: var(--deep);">
      {!! $berita->konten !!}
    </div>

    <!-- Share & Navigation -->
    <div class="mt-20 pt-10 border-t flex flex-col md:flex-row justify-between items-center gap-8" style="border-color: rgba(62,207,142,0.15);">
      <div class="flex items-center gap-4">
        <span class="text-sm font-bold uppercase tracking-widest" style="color: rgba(2,52,48,0.5);">Bagikan:</span>
        <div class="flex gap-2">
          @foreach(['facebook', 'twitter', 'whatsapp'] as $social)
          <button class="w-12 h-12 rounded-full flex items-center justify-center transition-all hover:-translate-y-1" style="background: rgba(62,207,142,0.1); color: var(--teal);">
            <i class="mdi mdi-{{ $social }} text-xl"></i>
          </button>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Related News -->
    @if($recent_news->count() > 0)
    <div class="mt-24">
      <h3 class="text-2xl font-black mb-10" style="color: var(--deep);">Berita Terkait</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($recent_news->take(2) as $news)
        <a href="{{ route('public.news.show', $news->id) }}" class="group block bg-white rounded-3xl p-4 border transition-all hover:shadow-xl" style="border-color: rgba(62,207,142,0.15);">
          <div class="relative h-48 rounded-2xl overflow-hidden mb-4">
            @if($news->gambar)
              <img src="{{ Str::startsWith($news->gambar, ['http://', 'https://']) ? $news->gambar : asset('storage/' . $news->gambar) }}" alt="{{ $news->judul }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            @else
              <div class="w-full h-full flex items-center justify-center group-hover:scale-110 transition-transform duration-700" style="background: rgba(62,207,142,0.05);">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="color: rgba(15,154,123,0.3);">
                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                  <circle cx="8.5" cy="8.5" r="1.5"></circle>
                  <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
              </div>
            @endif
          </div>
          <div class="px-2 mb-1 flex items-center gap-2 text-xs font-bold text-teal-700">
            <i class="mdi mdi-account-circle"></i>
            <span>{{ $news->penulis_nama }}</span>
          </div>
          <h4 class="text-lg font-bold leading-tight px-2 pb-2" style="color: var(--deep);">{{ $news->judul }}</h4>
        </a>
        @endforeach
      </div>
    </div>
    @endif
  </div>
</section>
@endsection
