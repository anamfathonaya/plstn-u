@props(['label' => 'LIVE'])

<span {{ $attributes->class('inline-flex items-center gap-1 rounded-sm bg-live px-2 py-0.5 font-sans text-label text-on-inverse uppercase') }}>
  <span class="size-1.5 rounded-full bg-on-inverse" aria-hidden="true"></span>
  <span>{{ $label }}</span>
</span>
