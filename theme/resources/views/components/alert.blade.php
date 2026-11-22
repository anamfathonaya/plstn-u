{{-- Kelas dihitung di App\View\Components\Alert (tanpa logika di template). --}}
<div {{ $attributes->merge(['class' => "px-2 py-1 {$class}"]) }}>
  {{ $message ?? $slot }}
</div>
