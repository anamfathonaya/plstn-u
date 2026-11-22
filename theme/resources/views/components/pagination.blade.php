@props(['pagination' => ['prev' => null, 'next' => null, 'pages' => [], 'current' => 1, 'total' => 1], 'label' => 'Paginasi'])

@if (! empty($pagination['pages']))
  <nav aria-label="{{ $label }}" {{ $attributes->class('flex flex-wrap items-center justify-center gap-2 border-t border-subtle pt-6 font-sans text-nav') }}>
    @if ($pagination['prev'])
      <a href="{{ $pagination['prev'] }}" rel="prev" class="border border-subtle px-3 py-2 text-primary hover:border-strong">{{ __('Sebelumnya', 'sage') }}</a>
    @endif

    <ul class="flex flex-wrap items-center gap-2">
      @foreach ($pagination['pages'] as $page)
        @if ($page === null)
          <li class="px-1 text-muted" aria-hidden="true">…</li>
        @elseif ($page['current'])
          <li><span aria-current="page" aria-label="{{ __('Halaman', 'sage') }} {{ $page['label'] }}" class="block border border-strong bg-inverse px-3 py-2 text-on-inverse">{{ $page['label'] }}</span></li>
        @else
          <li><a href="{{ $page['url'] }}" aria-label="{{ __('Halaman', 'sage') }} {{ $page['label'] }}" class="block border border-subtle px-3 py-2 text-primary hover:border-strong">{{ $page['label'] }}</a></li>
        @endif
      @endforeach
    </ul>

    @if ($pagination['next'])
      <a href="{{ $pagination['next'] }}" rel="next" class="border border-subtle px-3 py-2 text-primary hover:border-strong">{{ __('Berikutnya', 'sage') }}</a>
    @endif
  </nav>
@endif
