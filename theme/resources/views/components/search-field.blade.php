@props([
  'placeholder' => 'Cari berita…',
])

<button
  type="button"
  aria-controls="search-modal"
  aria-haspopup="dialog"
  x-data
  x-on:click="$dispatch('open-search-modal')"
  {{ $attributes->class('flex h-10 w-60 items-center gap-2 border border-subtle bg-canvas px-3 text-left font-sans text-nav text-muted') }}
>
  <svg class="size-5 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="8.5" cy="8.5" r="5.5"/><path d="M13 13l4 4"/></svg>
  <span class="flex-1 truncate">{{ $placeholder }}</span>
  <span class="sr-only">{{ __('Buka pencarian', 'sage') }}</span>
</button>
