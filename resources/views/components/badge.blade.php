@props([
  'variant' => null,
  'color' => null,
  'textColor' => null,
])

@php
  $badgeClass = 'badge';
  
  if ($variant == 'icon') {
    $badgeClass .= " badge--icon";
  }
  
  $style = '';
  if ($color) {
    $style .= "background-color: {$color};";
  }
  
  if ($textColor) {
    $style .= "color: {$textColor};";
  }
@endphp

<span class="{{ $badgeClass }}" style="{{ $style }}">
  {{ $slot }}
</span>