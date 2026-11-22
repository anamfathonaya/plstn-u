@props(['title' => '', 'url' => '', 'image' => '', 'imageAlt' => '', 'excerpt' => '', 'time' => '', 'live' => false, 'updated' => '', 'updates' => []])

{{-- Slot bawaan: sub-poin (mis. <x-card-compact> berulang); dirender dengan garis kiri. --}}
<article {{ $attributes->class('flex flex-col gap-4') }}>
  <a href="{{ esc_url($url) }}" tabindex="-1" aria-hidden="true" class="block aspect-video overflow-hidden rounded-sm bg-placeholder">
    @if ($image !== '')
      <img src="{{ esc_url($image) }}" alt="{{ $imageAlt }}" fetchpriority="high" class="size-full object-cover">
    @else
      <x-image-placeholder :logo="$markUrl ?? ''" />
    @endif
  </a>
  <div class="flex flex-col gap-3">
    @if ($live || $updated !== '' || $time !== '')
      <div class="flex items-center gap-2">
        @if ($live)
          <x-live-badge />
        @endif
        @if ($updated !== '' || $time !== '')
          <p class="font-sans text-meta text-muted">{{ $updated !== '' ? $updated : $time }}</p>
        @endif
      </div>
    @endif
    <h2 class="font-serif text-hero-mobile text-primary md:text-hero">
      <a href="{{ esc_url($url) }}" class="hover:underline">{{ $title }}</a>
    </h2>
    @if ($excerpt !== '')
      <p class="font-sans text-excerpt text-muted">{{ $excerpt }}</p>
    @endif
    @if ($updates)
      <ul class="flex flex-col gap-2 border-l border-subtle pl-3">
        @foreach ($updates as $update)
          <li class="flex items-baseline gap-3">
            <span class="w-16 shrink-0 font-sans text-meta text-live">{{ $update['time'] }}</span>
            <a href="{{ esc_url($update['url']) }}" class="font-sans text-compact text-primary hover:underline">{{ $update['title'] }}</a>
          </li>
        @endforeach
      </ul>
    @endif
    @if (! $slot->isEmpty())
      <div class="flex flex-col border-l border-subtle pl-3">{{ $slot }}</div>
    @endif
  </div>
</article>
