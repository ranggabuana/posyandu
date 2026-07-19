@props([
    'title',
    'subtitle' => null,
    'icon' => null,
    'breadcrumbs' => []
])

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <!-- Breadcrumbs -->
        <div class="flex flex-wrap items-center gap-1.5 text-xs text-gray-500 mb-1">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                <i class="mdi mdi-home text-sm"></i>
                <span>Beranda</span>
            </a>

            @if(is_array($breadcrumbs) && count($breadcrumbs) > 0)
                @foreach($breadcrumbs as $label => $link)
                    <i class="mdi mdi-chevron-right text-gray-400"></i>
                    @if($link)
                        <a href="{{ $link }}" class="hover:text-blue-600 transition">{{ $label }}</a>
                    @else
                        <span class="text-gray-900 font-semibold">{{ $label }}</span>
                    @endif
                @endforeach
            @endif
        </div>

        <!-- Page Title & Icon -->
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
            @if($icon)
                <i class="mdi {{ $icon }} text-blue-600 text-2xl"></i>
            @endif
            <span>{{ $title }}</span>
        </h1>

        @if($subtitle)
            <p class="text-xs text-gray-500 mt-1">{!! $subtitle !!}</p>
        @endif
    </div>

    <!-- Right Side Actions -->
    @if(isset($slot) && trim($slot) !== '')
        <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
            {{ $slot }}
        </div>
    @endif
</div>
