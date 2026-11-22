@extends('layouts.app')

@section('content')
  <x-category-strip />

  <div class="mx-auto flex max-w-page flex-col gap-12 px-4 py-6 md:px-0 md:py-8">
    <section class="flex max-w-article flex-col gap-4">
      <p class="font-sans text-label uppercase text-accent">404</p>
      <h1 class="font-serif text-hero-mobile text-primary md:text-hero">{{ __('Halaman tidak ditemukan', 'sage') }}</h1>
      <p class="font-sans text-excerpt text-muted">{{ __('Tautan yang Anda buka mungkin salah ketik atau halamannya sudah dipindahkan. Coba cari berita yang Anda butuhkan.', 'sage') }}</p>
      <x-search-form :action="$searchUrl" />
    </section>

    @if ($latest)
      <section class="flex flex-col gap-4" aria-label="{{ __('Kabar Terbaru', 'sage') }}">
        <x-section-header :title="__('Kabar Terbaru', 'sage')" :url="$latestUrl" :link-label="__('Semua', 'sage')" />
        <div class="grid gap-8 md:grid-cols-4">
          @foreach ($latest as $item)
            <x-card-standard :title="$item['title']" :url="$item['url']" :image="$item['image']" :image-alt="$item['imageAlt']" :kicker="$item['kicker']" :time="$item['time']" />
          @endforeach
        </div>
      </section>
    @endif
  </div>
@endsection
