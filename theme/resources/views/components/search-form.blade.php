@props([
  'action' => '',
  'query' => '',
])

<form role="search" method="get" action="{{ $action }}" {{ $attributes->class('flex items-center gap-3 border-b border-strong pb-3') }}>
  <input
    type="search"
    name="s"
    value="{{ $query }}"
    autocomplete="off"
    placeholder="{{ __('Cari berita…', 'sage') }}"
    aria-label="{{ __('Cari berita', 'sage') }}"
    class="min-w-0 flex-1 bg-transparent font-sans text-excerpt text-primary placeholder:text-muted focus:outline-none"
  >
  <button type="submit" class="border border-subtle px-3 py-2 font-sans text-nav text-primary hover:border-strong">{{ __('Cari', 'sage') }}</button>
</form>
