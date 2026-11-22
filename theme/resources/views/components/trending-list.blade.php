@props(['title' => 'Terpopuler', 'url' => '', 'limit' => 5])

{{-- Data: $trending dari View Composer Trending (title, url). --}}
@if (count($trending()) > 0)
<section {{ $attributes }}>
  <x-section-header :title="$title" :url="$url" />
  <ol>
    @foreach ($trending as $item)
      @break($loop->iteration > $limit)
      <li class="flex items-start gap-4 border-b border-subtle py-3 last:border-b-0">
        <span class="w-6 shrink-0 text-center font-serif text-trending text-primary" aria-hidden="true">{{ $loop->iteration }}</span>
        <a href="{{ $item['url'] }}" class="font-sans text-compact text-primary hover:underline">{{ $item['title'] }}</a>
      </li>
    @endforeach
  </ol>
</section>
@endif
