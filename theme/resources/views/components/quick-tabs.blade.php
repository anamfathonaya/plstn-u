@props(['default' => 'utama', 'label' => 'Kabar'])

{{-- Tab dari $quickTabs (View Composer App). Isi panel lewat slot bernama dengan id tab: <x-slot:utama>, <x-slot:terbaru>. --}}
<div {{ $attributes }} x-data="{ tab: '{{ $default }}' }">
  <div role="tablist" aria-label="{{ $label }}" class="mx-auto flex w-fit gap-1 rounded-full border border-subtle bg-canvas p-1 font-sans text-nav">
    @foreach ($quickTabs as $id => $tabLabel)
      <button type="button" role="tab" id="tab-{{ $id }}" aria-controls="panel-{{ $id }}" aria-selected="{{ $id === $default ? 'true' : 'false' }}" x-bind:aria-selected="tab === '{{ $id }}'" x-on:click="tab = '{{ $id }}'" class="rounded-full px-4 py-1 aria-selected:bg-inverse aria-selected:text-on-inverse text-muted">{{ $tabLabel }}</button>
    @endforeach
  </div>

  @foreach ($quickTabs as $id => $tabLabel)
    <div role="tabpanel" class="mt-6" id="panel-{{ $id }}" aria-labelledby="tab-{{ $id }}" tabindex="0" @if ($id !== $default) hidden @endif x-bind:hidden="tab !== '{{ $id }}'">
      @isset(${$id}) {{ ${$id} }} @endisset
    </div>
  @endforeach
</div>
