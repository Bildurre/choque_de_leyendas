@props([
  'type' => 'default',
  'color' => null,
  'textColor' => null,
])

@php
  $badgeClass = 'entity-badge';
  
  if ($type) {
    $badgeClass .= " entity-badge-{$type}";
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