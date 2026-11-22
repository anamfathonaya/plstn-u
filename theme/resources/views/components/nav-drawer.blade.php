@props([
  'siteName' => '',
  'logo' => '',
  'homeUrl' => '/',
  'live' => false,
  'liveUrl' => '',
  'liveLabel' => 'Live Update',
  'categoryLabel' => 'Kategori',
  'footerText' => '',
])

<dialog
  id="nav-drawer"
  closedby="any"
  aria-label="{{ __('Menu navigasi', 'sage') }}"
  x-data="{ open: null }"
  x-on:open-nav-drawer.window="$el.showModal()"
  {{ $attributes->class('m-0 mr-auto h-dvh max-h-none w-80 max-w-full bg-canvas p-0 text-primary md:ml-auto md:mr-0') }}
>
  <div class="flex h-full flex-col">
    <div class="flex h-13 shrink-0 items-center gap-2 border-b border-subtle pr-2 pl-4">
      <a href="{{ $homeUrl }}" class="flex items-center gap-2 font-serif text-logo text-primary">
        @if ($logo)
          <img src="{{ $logo }}" alt="" width="28" height="28" class="size-7">
        @endif
        <span>{{ $siteName }}</span>
      </a>
      <form method="dialog" class="ml-auto">
        <button type="submit" class="flex size-10 items-center justify-center" aria-label="{{ __('Tutup menu', 'sage') }}">
          <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 5l10 10M15 5L5 15"/></svg>
        </button>
      </form>
    </div>

    <nav class="flex-1 overflow-y-auto overscroll-contain" aria-label="{{ __('Menu utama', 'sage') }}">
      @if ($live)
        <a href="{{ $liveUrl }}" class="flex h-12 items-center gap-2 px-4 font-sans text-nav text-live">
          <span class="size-2 rounded-full bg-live" aria-hidden="true"></span>
          <span>{{ $liveLabel }}</span>
        </a>
      @endif

      @if (count($navItems()) > 0)
      <p class="px-4 pt-4 pb-2 font-sans text-label text-muted uppercase">{{ $categoryLabel }}</p>

      <ul>
        @foreach (($navItems ?? []) as $i => $item)
          <li>
            <div class="flex h-12 items-center gap-2 pl-4 {{ empty($item['children']) ? 'pr-4' : 'pr-2' }}">
              <a href="{{ $item['url'] }}" class="flex-1 font-sans text-compact text-primary">{{ $item['label'] }}</a>
              @if (! empty($item['children']))
                <button
                  type="button"
                  class="flex size-10 items-center justify-center"
                  aria-expanded="false"
                  aria-controls="nav-sub-{{ $i }}"
                  aria-label="{{ sprintf(__('Subkategori %s', 'sage'), $item['label']) }}"
                  x-bind:aria-expanded="open === {{ $i }}"
                  x-on:click="open = open === {{ $i }} ? null : {{ $i }}"
                >
                  <svg class="size-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" x-bind:class="open === {{ $i }} && 'rotate-180'"><path d="M5 8l5 5 5-5"/></svg>
                </button>
              @endif
            </div>
            @if (! empty($item['children']))
              <ul id="nav-sub-{{ $i }}" class="py-1" hidden x-bind:hidden="open !== {{ $i }}">
                @foreach ($item['children'] as $child)
                  <li>
                    <a href="{{ $child['url'] }}" class="flex h-10 items-center pr-4 pl-8 font-sans text-nav text-muted">{{ $child['label'] }}</a>
                  </li>
                @endforeach
              </ul>
            @endif
          </li>
        @endforeach
      </ul>
      @endif
    </nav>

    @if ($footerText)
      <p class="shrink-0 border-t border-subtle px-4 pt-4 pb-6 font-sans text-meta text-muted">{{ $footerText }}</p>
    @endif
  </div>
</dialog>
