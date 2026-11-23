@props([
  'siteName' => '',
  'tagline' => '',
  'copyright' => '',
  'follow' => [],
  'social' => [],
  'columns' => [],
])

{{-- Figma: Footer (Device=Desktop / Device=Mobile). Bagian kosong tidak dirender. --}}
<footer {{ $attributes->class('bg-inverse px-4 pt-10 pb-8 text-on-inverse md:px-0 md:pt-12') }}>
  <div class="mx-auto flex max-w-page flex-col gap-8">
    @if ($follow)
      <ul class="flex flex-col gap-4 md:grid md:grid-cols-3 md:gap-6">
        @foreach ($follow as $item)
          <li>
            <a href="{{ $item['url'] }}" class="group flex items-center gap-3">
              <x-social-icon :name="$item['key']" class="size-7" />
              <span class="flex flex-col">
                <span class="font-sans text-meta">{{ __('Ikuti di', 'plstn-u') }}</span>
                <span class="font-sans text-section-title group-hover:underline">{{ $item['label'] }}</span>
              </span>
            </a>
          </li>
        @endforeach
      </ul>
      <hr class="border-muted">
    @endif

    @if ($social)
      <ul class="flex flex-wrap gap-x-4 gap-y-3 md:gap-x-6">
        @foreach ($social as $item)
          <li>
            <a href="{{ $item['url'] }}" class="flex items-center gap-2 font-sans text-nav hover:underline" @if ($item['key'] !== 'rss') rel="me" @endif>
              <x-social-icon :name="$item['key']" class="size-5" />
              {{ $item['label'] }}
            </a>
          </li>
        @endforeach
      </ul>
      <hr class="border-muted">
    @endif

    <div class="grid grid-cols-2 gap-x-6 gap-y-8 md:grid-cols-3">
      <div class="col-span-2 flex flex-col gap-2 md:col-span-1">
        <p class="font-serif text-logo">{{ $siteName }}</p>
        @if ($tagline)
          <p class="font-sans text-meta">{{ $tagline }}</p>
        @endif
      </div>

      @foreach ($columns as $column)
        <nav class="flex flex-col gap-3" @if ($column['heading']) aria-label="{{ $column['heading'] }}" @endif>
          @if ($column['heading'])
            <p class="font-sans text-label uppercase">{{ $column['heading'] }}</p>
          @endif
          <ul class="flex flex-col gap-3">
            @foreach ($column['links'] as $link)
              <li><a href="{{ $link['url'] }}" class="block font-sans text-nav hover:underline">{{ $link['label'] }}</a></li>
            @endforeach
          </ul>
        </nav>
      @endforeach
    </div>

    <hr class="border-muted">

    <div class="flex items-center justify-between gap-4 font-sans text-meta">
      <p>{{ $copyright }}</p>
      <a href="#app" class="hover:underline">{{ __('Ke atas', 'plstn-u') }} <span aria-hidden="true">↑</span></a>
    </div>
  </div>
</footer>
