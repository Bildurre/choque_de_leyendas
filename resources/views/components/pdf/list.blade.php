@props([
  'items' => collect(),
  'type' => 'faction', // faction, deck, or other
  'emptyMessage' => __('admin.no_pdfs_found'),
])

@php
  // Convertir a Collection si es un array
  $items = $items instanceof \Illuminate\Support\Collection ? $items : collect($items);
@endphp

<div {{ $attributes->merge(['class' => 'pdf-list']) }}>
  @if($items->isEmpty())
    <div class="pdf-list__empty">
      <p>{{ $emptyMessage }}</p>
    </div>
  @else
    <div class="pdf-list__items">
      {{ $slot }}
    </div>
  @endif
</div>