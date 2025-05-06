@props([
  'variant' => 'primary',
  'size' => 'md'
])

@php
  $baseClass = 'badge';
  $variantClass = "badge--$variant";
  $sizeClass = "badge--$size";
@endphp

<span {{ $attributes->merge(['class' => "$baseClass $variantClass $sizeClass"]) }}>
  {{ $slot }}
</span>