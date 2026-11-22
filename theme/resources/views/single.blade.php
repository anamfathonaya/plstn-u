@extends('layouts.app')

@section('content')
  <x-category-strip />

  @if ($article)
    <div class="mx-auto flex max-w-page flex-col gap-12 px-4 py-6 md:px-0 md:py-8">
      <div class="flex flex-col gap-8 md:flex-row">
        <article class="h-entry flex min-w-0 max-w-article flex-1 flex-col gap-6">
          <header class="flex flex-col gap-3">
            @if ($article['kicker'] !== '')
              <p class="font-sans text-label uppercase text-accent">
                <a href="{{ $article['kickerUrl'] }}" class="hover:underline">{{ $article['kicker'] }}</a>
              </p>
            @endif
            <h1 class="p-name font-serif text-hero-mobile text-primary md:text-hero">{{ $article['title'] }}</h1>
            @if ($article['excerpt'] !== '')
              <p class="font-sans text-excerpt text-muted">{{ $article['excerpt'] }}</p>
            @endif
            <div class="flex flex-wrap items-center justify-between gap-3 border-y border-subtle py-3">
              <p class="font-sans text-meta text-muted">
                @if ($article['author'] !== '')
                  <span class="p-author">{{ $article['author'] }}</span> &middot;
                @endif
                <time class="dt-published" datetime="{{ $article['datetime'] }}">{{ $article['date'] }}</time>
              </p>
              <x-font-resizer />
            </div>
          </header>

          <figure class="flex flex-col gap-2">
            <div class="aspect-video overflow-hidden rounded-sm bg-placeholder">
              @if ($article['image'] !== '')
                <img src="{{ $article['image'] }}" alt="{{ $article['imageAlt'] }}" fetchpriority="high" class="size-full object-cover">
              @else
                <x-image-placeholder :logo="$logoUrl ?? ''" />
              @endif
            </div>
            @if ($article['image'] !== '' && ($article['caption'] !== '' || $article['sourceName'] !== ''))
              <figcaption class="font-sans text-meta text-muted">
                {{ $article['caption'] }}
                @if ($article['sourceName'] !== '')
                  {{ __('Foto/Sumber:', 'sage') }} {{ $article['sourceName'] }}
                @endif
              </figcaption>
            @endif
          </figure>

          <div class="article-body e-content font-serif text-body-mobile text-primary md:text-body">
            {!! $article['content'] !!}
          </div>

          @if ($article['sourceName'] !== '' || $article['sourceUrl'] !== '')
            <p class="border-t border-subtle pt-4 font-sans text-nav text-muted">
              {{ __('Sumber:', 'sage') }}
              @if ($article['sourceUrl'] !== '')
                <a href="{{ $article['sourceUrl'] }}" rel="noopener nofollow" target="_blank" class="text-primary underline">{{ $article['sourceName'] !== '' ? $article['sourceName'] : $article['sourceUrl'] }}</a>
              @else
                {{ $article['sourceName'] }}
              @endif
            </p>
          @endif

          @if ($article['categories'] || $article['tags'])
            <ul class="flex flex-wrap gap-2" aria-label="{{ __('Kategori dan tag', 'sage') }}">
              @foreach ($article['categories'] as $term)
                <li><a href="{{ $term['url'] }}" class="block rounded-full border border-strong px-3 py-1 font-sans text-nav text-primary hover:bg-surface">{{ $term['name'] }}</a></li>
              @endforeach
              @foreach ($article['tags'] as $term)
                <li><a href="{{ $term['url'] }}" class="block rounded-full border border-subtle px-3 py-1 font-sans text-nav text-muted hover:bg-surface">#{{ $term['name'] }}</a></li>
              @endforeach
            </ul>
          @endif

          @if ($related)
            <section class="flex flex-col gap-4 md:hidden" aria-label="{{ __('Berita Terkait', 'sage') }}">
              <x-section-header :title="__('Berita Terkait', 'sage')" :url="$relatedUrl" />
              <div class="flex flex-col divide-y divide-subtle">
                @foreach ($related as $item)
                  <x-card-compact :title="$item['title']" :url="$item['url']" :image="$item['image']" :image-alt="$item['imageAlt']" :time="$item['time']" :thumb="true" />
                @endforeach
              </div>
            </section>
          @endif
        </article>

        <aside class="flex w-full flex-col gap-8 md:w-86" aria-label="{{ __('Sorotan', 'sage') }}">
          <x-trending-list />

          @if ($latest)
            <section class="hidden flex-col gap-4 md:flex">
              <x-section-header :title="__('Kabar Terbaru', 'sage')" :url="$latestUrl" :link-label="__('Semua', 'sage')" />
              <div class="flex flex-col divide-y divide-subtle">
                @foreach ($latest as $item)
                  <x-card-compact :title="$item['title']" :url="$item['url']" :time="$item['time']" :thumb="false" />
                @endforeach
              </div>
            </section>
          @endif
        </aside>
      </div>

      @if ($related)
        <section class="hidden flex-col gap-4 md:flex" aria-label="{{ __('Berita Terkait', 'sage') }}">
          <x-section-header :title="__('Berita Terkait', 'sage')" :url="$relatedUrl" />
          <div class="grid gap-8 md:grid-cols-3">
            @foreach ($related as $item)
              <x-card-standard :title="$item['title']" :url="$item['url']" :image="$item['image']" :image-alt="$item['imageAlt']" :kicker="$item['kicker']" :time="$item['time']" />
            @endforeach
          </div>
        </section>
      @endif
    </div>
  @endif
@endsection
