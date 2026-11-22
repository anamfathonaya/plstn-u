@props(['lead' => [], 'items' => []])

{{-- Kolom samping hero: 1 kartu utama bergambar + daftar compact. --}}
<div {{ $attributes->class('flex flex-col gap-3') }}>
  @if ($lead['title'] !== '')
  <x-card-standard :title="$lead['title']" :url="$lead['url']" :image="$lead['image']" :image-alt="$lead['imageAlt']" :kicker="$lead['kicker']" :time="$lead['time']" />
  @endif
  <div class="flex flex-col divide-y divide-subtle">
    @foreach ($items as $item)
      <x-card-compact :title="$item['title']" :url="$item['url']" :time="$item['time']" />
    @endforeach
  </div>
</div>
