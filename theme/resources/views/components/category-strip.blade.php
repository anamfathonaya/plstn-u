@props(['label' => 'Kategori'])

{{-- Data: $categoryStrip dari View Composer App (label, url, active). Scroll native; pudar tepi lewat CSS (.category-strip). --}}
@if (count($categoryStrip()) > 1)
<nav {{ $attributes->class('border-b border-subtle bg-canvas') }} aria-label="{{ $label }}">
  <div class="mx-auto max-w-page">
    <ul class="category-strip flex gap-6 overflow-x-auto px-4 font-sans text-nav md:px-0">
      @foreach ($categoryStrip as $item)
        <li class="shrink-0">
          <a href="{{ $item['url'] }}" @if ($item['active']) aria-current="page" @endif class="-mb-px block whitespace-nowrap border-b-2 py-3 {{ $item['active'] ? 'border-strong text-primary' : 'border-transparent text-muted hover:text-primary' }}">{{ $item['label'] }}</a>
        </li>
      @endforeach
    </ul>
  </div>
</nav>
@endif
