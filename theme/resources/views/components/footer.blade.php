@props([
  'siteName' => '',
  'copyright' => '',
  'items' => [],
])

<footer {{ $attributes->class('bg-inverse text-on-inverse') }}>
  <div class="mx-auto flex max-w-page flex-col gap-3 px-4 py-8 md:flex-row md:items-center md:justify-between md:px-0 md:py-10">
    <p class="font-serif text-logo">{{ $siteName }}</p>
    <div class="flex flex-wrap items-center gap-x-2 font-sans text-meta">
      <span>{{ $copyright }}</span>
      @foreach ($items as $link)
        <span aria-hidden="true">·</span>
        <a href="{{ esc_url($link['url']) }}" class="hover:underline">{{ $link['label'] }}</a>
      @endforeach
    </div>
  </div>
</footer>
