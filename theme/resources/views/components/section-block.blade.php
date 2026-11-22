@props(['title' => '', 'url' => '', 'items' => []])

{{-- Blok rubrik: desktop 4 kartu standar; mobile 1 kartu utama + daftar compact berthumbnail. --}}
<section {{ $attributes->class('flex flex-col gap-4') }}>
  <x-section-header :title="$title" :url="$url" />

  <div class="hidden gap-8 md:grid md:grid-cols-4">
    @foreach ($items as $item)
      <x-card-standard :title="$item['title']" :url="$item['url']" :image="$item['image']" :image-alt="$item['imageAlt']" :kicker="$item['kicker']" :time="$item['time']" />
    @endforeach
  </div>

  <div class="flex flex-col md:hidden">
    @foreach ($items as $item)
      @if ($loop->first)
        <x-card-standard class="pb-3" :title="$item['title']" :url="$item['url']" :image="$item['image']" :image-alt="$item['imageAlt']" :kicker="$item['kicker']" :time="$item['time']" />
      @else
        <x-card-compact class="border-t border-subtle" :title="$item['title']" :url="$item['url']" :image="$item['image']" :image-alt="$item['imageAlt']" :time="$item['time']" :thumb="true" />
      @endif
    @endforeach
  </div>
</section>
