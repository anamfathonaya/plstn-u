@props([
  'siteName' => '',
  'shortName' => 'PU',
  'logo' => '',
  'homeUrl' => '/',
  'live' => false,
  'liveUrl' => '',
  'searchUrl' => '',
  'footerText' => '',
])

<header {{ $attributes->class('sticky top-0 z-10 border-b border-subtle bg-canvas') }}>
  <div class="relative mx-auto flex h-13 max-w-page items-center gap-3 px-4 md:h-14 md:gap-6 md:px-0">
    {{-- Tombol menu: mobile di kiri --}}
    <button type="button" class="flex size-10 items-center justify-center md:hidden" aria-controls="nav-drawer" aria-label="{{ __('Buka menu', 'sage') }}" x-data x-on:click="$dispatch('open-nav-drawer')">
      <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 5h16M2 10h16M2 15h16"/></svg>
    </button>

    <a href="{{ $homeUrl }}" class="absolute left-1/2 flex -translate-x-1/2 items-center gap-2 font-serif text-logo text-primary md:static md:translate-x-0" aria-label="{{ $siteName }}">
      @if ($logo)
        <img src="{{ $logo }}" alt="" height="28" class="h-7 w-auto object-contain">
      @endif
      <span class="md:hidden" aria-hidden="true">{{ $shortName }}</span>
      <span class="hidden md:inline" aria-hidden="true">{{ $siteName }}</span>
    </a>

    <div class="ml-auto flex items-center gap-2 md:gap-4">
      @if ($live && $liveUrl)
        <a href="{{ $liveUrl }}" class="flex h-9 items-center gap-2 rounded-full bg-inverse px-3 font-sans text-nav text-on-inverse md:px-4">
          <span class="size-2 rounded-full bg-live" aria-hidden="true"></span>
          <span class="md:hidden">{{ __('Live', 'sage') }}</span>
          <span class="hidden md:inline">{{ __('Live Update', 'sage') }}</span>
        </a>
      @endif

      @if ($searchUrl)
        <x-search-field class="hidden md:flex" />
        <button type="button" class="flex size-10 items-center justify-center md:hidden" aria-controls="search-modal" aria-label="{{ __('Cari berita', 'sage') }}" x-data x-on:click="$dispatch('open-search-modal')">
          <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="8.5" cy="8.5" r="5.5"/><path d="M13 13l4 4"/></svg>
        </button>
      @endif

      {{-- Tombol menu: desktop di kanan --}}
      <button type="button" class="hidden size-10 items-center justify-center md:flex" aria-controls="nav-drawer" aria-label="{{ __('Buka menu', 'sage') }}" x-data x-on:click="$dispatch('open-nav-drawer')">
        <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 5h16M2 10h16M2 15h16"/></svg>
      </button>
    </div>
  </div>

  @if ($searchUrl)
    <x-search-modal :action="$searchUrl" />
  @endif

  <x-nav-drawer :site-name="$siteName" :logo="$logo" :home-url="$homeUrl" :live="$live" :live-url="$liveUrl" :footer-text="$footerText" />
</header>
