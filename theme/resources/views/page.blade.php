@extends('layouts.app')

@section('content')
  <x-category-strip />

  @if ($page)
    <article class="mx-auto flex max-w-page flex-col gap-6 px-4 py-6 md:px-0 md:py-8">
      <div class="flex max-w-article flex-col gap-6">
        <h1 class="font-serif text-hero-mobile text-primary md:text-hero">{{ $page['title'] }}</h1>
        <div class="article-body font-serif text-body-mobile text-primary md:text-body">
          {!! $page['content'] !!}
        </div>
      </div>
    </article>
  @endif
@endsection
