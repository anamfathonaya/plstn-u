@extends('layouts.app')

@section('content')
  <h1 class="sr-only">{{ $siteName }}</h1>
  <x-category-strip />

  <div class="mx-auto flex max-w-page flex-col gap-12 px-4 py-6 md:px-0 md:py-8">
    @unless ($hasPosts)
      <p class="py-16 text-center font-serif text-hero-mobile text-muted">{{ __('Belum ada berita.', 'plstn-u') }}</p>
    @endunless
    @if ($hasPosts)
    {{-- Tab Utama / Terbaru di atas hero --}}
    <x-quick-tabs>
      <x-slot:utama>
        {{-- Hero: 3 kolom desktop (kiri / hero / kanan), hero lebih dulu di mobile --}}
        <section class="grid gap-8 md:grid-cols-4" aria-label="{{ __('Berita utama', 'plstn-u') }}">
          @if ($heroLeft['lead']['title'] !== '')
            <x-hero-column class="hidden md:order-1 md:flex" :lead="$heroLeft['lead']" :items="$heroLeft['items']" />
          @endif

          <x-hero-headline class="md:order-2 {{ $heroSides === 0 ? 'md:col-span-4' : ($heroSides === 1 ? 'md:col-span-3' : 'md:col-span-2') }}" :title="$hero['title']" :url="$hero['url']" :image="$hero['image']" :image-alt="$hero['imageAlt']" :excerpt="$hero['excerpt']" :live="$liveActive()" :updated="$hero['updated']" :updates="$hero['updates']" />

          @if ($heroRight['lead']['title'] !== '')
            <x-hero-column class="hidden md:order-3 md:flex" :lead="$heroRight['lead']" :items="$heroRight['items']" />
          @endif
        </section>
      </x-slot:utama>
      <x-slot:terbaru>
        <div class="flex flex-col divide-y divide-subtle">
          @foreach ($newsLatest as $item)
            <x-card-compact :title="$item['title']" :url="$item['url']" :image="$item['image']" :time="$item['time']" :thumb="true" />
          @endforeach
        </div>
      </x-slot:terbaru>
    </x-quick-tabs>

    @foreach ($sections as $section)
      @if ($section['slot'] === 'news')
        {{-- Kabar Terbaru + Terpopuler --}}
        <div class="grid gap-8 md:grid-cols-3">
          <section class="flex flex-col gap-4 md:col-span-2">
            <x-section-header :title="$section['title']" :url="$section['url']" />
            <div class="flex flex-col divide-y divide-subtle">
              @foreach ($section['items'] as $item)
                <x-card-compact :title="$item['title']" :url="$item['url']" :image="$item['image']" :time="$item['time']" :thumb="true" />
              @endforeach
            </div>
          </section>

          <x-trending-list class="md:col-span-1" />
        </div>
      @else
        <x-section-block :title="$section['title']" :url="$section['url']" :items="$section['items']" />
      @endif
    @endforeach
    @endif
  </div>
@endsection
