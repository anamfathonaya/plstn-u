@props([
  'action' => '/',
  'placeholder' => 'Cari berita, topik, atau tempat…',
  'popularLabel' => 'Topik populer',
])

<dialog
  id="search-modal"
  closedby="any"
  aria-label="{{ __('Cari berita', 'sage') }}"
  x-data
  x-on:open-search-modal.window="$el.showModal()"
  {{ $attributes->class('mx-auto mt-16 mb-auto w-160 max-w-full border border-subtle bg-canvas p-6 text-primary') }}
>
  <form role="search" method="get" action="{{ $action }}" class="flex flex-col gap-6">
    <div class="flex items-center gap-3 border-b border-strong pb-3">
      <svg class="size-5 shrink-0 text-muted" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="8.5" cy="8.5" r="5.5"/><path d="M13 13l4 4"/></svg>
      <input
        type="search"
        name="s"
        autofocus
        autocomplete="off"
        placeholder="{{ $placeholder }}"
        aria-label="{{ __('Cari berita', 'sage') }}"
        class="min-w-0 flex-1 bg-transparent font-sans text-excerpt text-primary placeholder:text-muted focus:outline-none"
      >
      <kbd class="hidden border border-subtle px-2 py-0.5 font-sans text-meta text-muted md:block" aria-hidden="true">Esc</kbd>
    </div>
  </form>

  @if (count($popularTopics()) > 0)
    <div class="mt-6">
      <p class="mb-3 font-sans text-label text-muted uppercase">{{ $popularLabel }}</p>
      <ul class="flex flex-wrap gap-2">
        @foreach ($popularTopics as $topic)
          <li>
            <a href="{{ $topic['url'] }}" class="flex items-center border border-subtle px-3 py-1 font-sans text-nav text-primary">{{ $topic['label'] }}</a>
          </li>
        @endforeach
      </ul>
    </div>
  @endif
</dialog>
