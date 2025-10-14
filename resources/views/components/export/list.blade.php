@props([
  'items' => collect(),
  'emptyMessage' => __('export.no_exports'),
])

@php
  $items = $items instanceof \Illuminate\Support\Collection ? $items : collect($items);
@endphp

<div {{ $attributes->merge(['class' => 'export-list']) }}>
  @if($items->isEmpty())
    <div class="export-list__empty">
      <p>{{ $emptyMessage }}</p>
    </div>
  @else
    <div class="export-list__items">
      {{ $slot }}
    </div>
  @endif
</div>