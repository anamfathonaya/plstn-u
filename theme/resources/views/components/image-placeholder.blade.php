@props(['logo' => ''])

{{-- Pengisi saat post tanpa gambar unggulan; memenuhi bingkai induk (rasio dijaga induk). --}}
<span {{ $attributes->class('flex size-full items-center justify-center bg-placeholder') }} aria-hidden="true">
  @if ($logo !== '')
    <img src="{{ $logo }}" alt="" loading="lazy" class="h-1/2 max-h-16 w-auto opacity-40">
  @endif
</span>
