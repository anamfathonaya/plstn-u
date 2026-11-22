@props(['title' => '', 'url' => '', 'image' => '', 'imageAlt' => '', 'kicker' => '', 'time' => ''])

<article {{ $attributes->class('flex flex-col gap-2') }}>
  <a href="{{ esc_url($url) }}" tabindex="-1" aria-hidden="true" class="block aspect-video overflow-hidden rounded-sm bg-placeholder">
    @if ($image !== '')
      <img src="{{ esc_url($image) }}" alt="{{ $imageAlt }}" loading="lazy" class="size-full object-cover">
    @else
      <x-image-placeholder :logo="$logoUrl ?? ''" />
    @endif
  </a>
  @if ($kicker !== '')
    <p class="font-sans text-label uppercase text-accent">{{ $kicker }}</p>
  @endif
  <h3 class="font-serif text-card-mobile text-primary md:text-card">
    <a href="{{ esc_url($url) }}" class="hover:underline">{{ $title }}</a>
  </h3>
  @if ($time !== '')
    <p class="font-sans text-meta text-muted">{{ $time }}</p>
  @endif
</article>
