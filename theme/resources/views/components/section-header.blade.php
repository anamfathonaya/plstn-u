@props(['title' => '', 'url' => '', 'linkLabel' => 'Lihat semua', 'tag' => 'h2'])

<div {{ $attributes->class('flex items-center justify-between border-t-2 border-strong pt-3') }}>
  <{{ $tag }} class="flex items-center gap-2 font-sans text-section-title text-primary">
    <span class="size-2 rounded-full bg-accent" aria-hidden="true"></span>
    {{ $title }}
  </{{ $tag }}>
  @if ($url !== '')
    <a href="{{ esc_url($url) }}" class="font-sans text-nav text-muted hover:text-primary">{{ $linkLabel }}</a>
  @endif
</div>
