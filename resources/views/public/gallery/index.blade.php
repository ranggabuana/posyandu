@extends('layouts.public')

@section('title', 'Galeri')

@section('content')
    <section class="hero-bg relative min-h-[40vh] flex items-center pt-28 pb-16 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center justify-center gap-2 section-badge floating" style="margin-bottom: 1.5rem;">
                <span style="color: var(--teal);">Visualisasi Pelayanan Kami</span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-tight mb-6" style="color: var(--deep);">
                Galeri <span
                    style="background: linear-gradient(135deg, var(--teal), var(--mint)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Momen</span>
            </h1>
            <p class="text-lg md:text-xl max-w-2xl mx-auto font-medium" style="color: rgba(2,52,48,0.7);">
                Kumpulan dokumentasi kebersamaan dan dedikasi pelayanan Posyandu di setiap wilayah dusun.
            </p>
        </div>
        <!-- Wavy divider bottom -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none" style="transform: translateY(1px);">
            <svg class="w-full h-10 sm:h-16 lg:h-24" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.43,122.9,122,185.12,106.67Z"
                    fill="var(--cream)"></path>
            </svg>
        </div>
    </section>

    <section class="py-16" style="background-color: var(--cream);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Filter Posyandu -->
            <div class="mb-12 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('public.gallery.index') }}" 
                   class="px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-300 {{ !request('posyandu_id') ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/20' : 'bg-white text-teal-800 hover:bg-teal-50 border border-teal-600/10' }}"
                   style="{{ !request('posyandu_id') ? 'background-color: var(--teal); color: white;' : '' }}">
                    Semua Posyandu
                </a>
                @foreach($posyandus as $p)
                    <a href="{{ route('public.gallery.index', ['posyandu_id' => $p->id]) }}" 
                       class="px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-300 {{ request('posyandu_id') == $p->id ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/20' : 'bg-white text-teal-800 hover:bg-teal-50 border border-teal-600/10' }}"
                       style="{{ request('posyandu_id') == $p->id ? 'background-color: var(--teal); color: white;' : '' }}">
                        {{ $p->nama }}
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($galeries as $index => $item)
                    <div class="group relative bg-white rounded-3xl overflow-hidden border card-hover cursor-pointer gallery-item"
                        data-image="{{ Str::startsWith($item->foto, ['http://', 'https://']) ? $item->foto : asset('storage/' . $item->foto) }}"
                        data-title="{{ $item->judul }}"
                        data-posyandu="{{ $item->posyandu->nama ?? 'Umum' }}"
                        data-desc="{{ $item->keterangan ?? '' }}"
                        style="border-color: rgba(62,207,142,0.15); height: 350px;">
                        @if ($item->foto)
                            <img src="{{ Str::startsWith($item->foto, ['http://', 'https://']) ? $item->foto : asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}"
                                class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center transition-transform duration-1000 group-hover:scale-110"
                                style="background: rgba(62,207,142,0.05);">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                                    style="color: rgba(15,154,123,0.3);">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                            </div>
                        @endif

                        <!-- Advanced Overlay -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-8">
                            <div
                                class="space-y-3 transform translate-y-8 group-hover:translate-y-0 transition-transform duration-700">
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest"
                                        style="background: var(--teal); color: white;">
                                        {{ $item->posyandu->nama ?? 'Umum' }}
                                    </span>
                                    <span
                                        class="text-[10px] font-bold text-white uppercase tracking-widest">{{ $item->created_at->format('M Y') }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-white leading-tight">{{ $item->judul }}</h3>
                                @if ($item->keterangan)
                                    <p class="text-sm font-medium line-clamp-2" style="color: rgba(255,255,255,0.7);">
                                        {{ $item->keterangan }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6"
                            style="background: rgba(62,207,142,0.1); color: var(--teal);">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-3" style="color: var(--deep);">Galeri Kosong</h3>
                        <p class="text-sm font-medium mb-8" style="color: rgba(2,52,48,0.6);">Belum ada dokumentasi foto
                            kegiatan yang diunggah.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-16 flex justify-center">
                {{ $galeries->links() }}
            </div>
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div id="lightbox-modal" class="fixed inset-0 z-[100] hidden bg-black/90 backdrop-blur-md flex items-center justify-center p-4 transition-all duration-300 opacity-0 pointer-events-none">
        <!-- Close Button -->
        <button id="lightbox-close" class="absolute top-6 right-6 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-3 rounded-full transition-all duration-200 focus:outline-none cursor-pointer">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <!-- Modal Content Container -->
        <div class="max-w-4xl w-full flex flex-col items-center justify-center gap-4 relative">
            <img id="lightbox-image" src="" alt="Full View" class="max-h-[75vh] max-w-full object-contain rounded-2xl shadow-2xl transform scale-95 transition-transform duration-300">
            <div class="text-center text-white space-y-2 px-4 mt-2 max-w-2xl">
                <span id="lightbox-posyandu" class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-emerald-500/20 text-emerald-400 border border-emerald-500/30"></span>
                <h3 id="lightbox-title" class="text-xl md:text-2xl font-black tracking-tight leading-tight"></h3>
                <p id="lightbox-desc" class="text-sm font-medium text-gray-300 leading-relaxed"></p>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('lightbox-modal');
        const modalImg = document.getElementById('lightbox-image');
        const modalTitle = document.getElementById('lightbox-title');
        const modalPosyandu = document.getElementById('lightbox-posyandu');
        const modalDesc = document.getElementById('lightbox-desc');
        const closeBtn = document.getElementById('lightbox-close');
        const galleryItems = document.querySelectorAll('.gallery-item');

        galleryItems.forEach(item => {
            item.addEventListener('click', function () {
                const imageSrc = this.getAttribute('data-image');
                const title = this.getAttribute('data-title');
                const posyandu = this.getAttribute('data-posyandu');
                const desc = this.getAttribute('data-desc');

                modalImg.src = imageSrc;
                modalTitle.textContent = title;
                modalPosyandu.textContent = posyandu;
                modalDesc.textContent = desc;

                // Show modal with transition
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0', 'pointer-events-none');
                    modal.classList.add('opacity-100', 'pointer-events-auto');
                    modalImg.classList.remove('scale-95');
                    modalImg.classList.add('scale-100');
                }, 10);
            });
        });

        function closeModal() {
            modal.classList.remove('opacity-100', 'pointer-events-auto');
            modal.classList.add('opacity-0', 'pointer-events-none');
            modalImg.classList.remove('scale-100');
            modalImg.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function (e) {
            // Close if click is outside the content (directly on the overlay)
            if (e.target === modal || e.target.classList.contains('flex')) {
                closeModal();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>
@endsection
