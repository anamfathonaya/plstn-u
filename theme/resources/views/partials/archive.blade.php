<div class="mx-auto flex max-w-page flex-col gap-8 px-4 py-6 md:px-0 md:py-8">
  <header class="flex flex-col gap-3">
    <x-section-header :title="$archive['title']" tag="h1" />
    @if ($archive['description'] !== '')
      <p class="max-w-article font-sans text-excerpt text-muted">{{ $archive['description'] }}</p>
    @endif
  </header>

  @if ($archive['isSearch'])
    <form role="search" method="get" action="{{ $archive['searchAction'] }}" class="flex max-w-article items-center gap-3 border-b border-strong pb-3">
      <input
        type="search"
        name="s"
        value="{{ $archive['query'] }}"
        autocomplete="off"
        placeholder="{{ __('Cari berita…', 'sage') }}"
        aria-label="{{ __('Cari berita', 'sage') }}"
        class="min-w-0 flex-1 bg-transparent font-sans text-excerpt text-primary placeholder:text-muted focus:outline-none"
      >
      <button type="submit" class="border border-subtle px-3 py-2 font-sans text-nav text-primary hover:border-strong">{{ __('Cari', 'sage') }}</button>
    </form>
  @endif

  @if (empty($archive['items']))
    <section class="flex flex-col gap-2 py-8" aria-live="polite">
      <h2 class="font-serif text-card text-primary">{{ __('Tidak ada hasil', 'sage') }}</h2>
      <p class="font-sans text-excerpt text-muted">
        {{ $archive['isSearch'] ? __('Coba kata kunci lain atau periksa ejaan Anda.', 'sage') : __('Belum ada berita di bagian ini.', 'sage') }}
      </p>
    </section>
  @else
    @if ($archive['isSearch'])
      <p class="font-sans text-meta text-muted">{{ $archive['count'] }} {{ __('hasil ditemukan', 'sage') }}</p>
    @endif

    <div class="grid gap-8 md:grid-cols-4">
      @foreach ($archive['items'] as $item)
        <x-card-standard :title="$item['title']" :url="$item['url']" :image="$item['image']" :image-alt="$item['imageAlt']" :kicker="$item['kicker']" :time="$item['time']" />
      @endforeach
    </div>

    <x-pagination :pagination="$archive['pagination']" />
  @endif
</div>
