@props(['title' => '', 'url' => '', 'image' => '', 'imageAlt' => '', 'time' => '', 'thumb' => false])

{{-- Gambar opsional (96x64); tanpa gambar = teks padat. --}}
<article {{ $attributes->class('flex items-start gap-3 py-3') }}>
  <div class="flex min-w-0 flex-1 flex-col gap-1">
    <h3 class="font-sans text-compact text-primary">
      <a href="{{ esc_url($url) }}" class="hover:underline">{{ $title }}</a>
    </h3>
    @if ($time !== '')
      <p class="font-sans text-meta text-muted">{{ $time }}</p>
    @endif
  </div>
  @if ($thumb || $image !== '')
    <a href="{{ esc_url($url) }}" tabindex="-1" aria-hidden="true" class="block h-16 w-24 shrink-0 overflow-hidden rounded-sm bg-placeholder">
      @if ($image !== '')
        <img src="{{ esc_url($image) }}" alt="{{ $imageAlt }}" loading="lazy" class="size-full object-cover">
      @else
        <x-image-placeholder :logo="$logoUrl ?? ''" />
      @endif
    </a>
  @endif
</article>
