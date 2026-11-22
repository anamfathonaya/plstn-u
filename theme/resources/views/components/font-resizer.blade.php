@props([
  'label' => 'Ukuran teks',
  'normalLabel' => 'Teks normal',
  'largeLabel' => 'Teks besar',
])

<div
  role="group"
  aria-label="{{ $label }}"
  x-data="{
    size: 'normal',
    init() {
      try { this.size = localStorage.getItem('pu-text-size') === 'large' ? 'large' : 'normal' } catch (e) {}
      this.apply()
    },
    set(v) {
      this.size = v
      try { localStorage.setItem('pu-text-size', v) } catch (e) {}
      this.apply()
    },
    apply() { document.documentElement.dataset.textSize = this.size },
  }"
  {{ $attributes->class('inline-flex items-center gap-1 rounded-full border border-subtle p-1') }}
>
  <button
    type="button"
    aria-label="{{ $normalLabel }}"
    aria-pressed="true"
    x-bind:aria-pressed="size === 'normal'"
    x-on:click="set('normal')"
    class="rounded-full px-4 py-1 font-sans text-nav text-muted aria-pressed:bg-inverse aria-pressed:text-on-inverse"
  >A</button>
  <button
    type="button"
    aria-label="{{ $largeLabel }}"
    aria-pressed="false"
    x-bind:aria-pressed="size === 'large'"
    x-on:click="set('large')"
    class="rounded-full px-4 py-1 font-sans text-nav text-muted aria-pressed:bg-inverse aria-pressed:text-on-inverse"
  >A+</button>
</div>
