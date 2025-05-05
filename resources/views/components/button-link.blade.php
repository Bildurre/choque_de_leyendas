@props([
  'href' => '#',
  'variant' => 'primary', 
  'size' => 'md',
  'icon' => null,
  'iconPosition' => 'left'
])

@php
  $baseClass = 'btn';
  $variantClass = "btn--$variant";
  $sizeClass = "btn--$size";
  $iconClass = $icon ? "btn--with-icon btn--icon-$iconPosition" : '';
@endphp

<a 
  href="{{ $href }}" 
  {{ $attributes->merge(['class' => "$baseClass $variantClass $sizeClass $iconClass"]) }}
>
  @if($icon && $iconPosition === 'left')
    <x-icon :name="$icon" size="sm" class="btn__icon" />
  @endif
  
  <span class="btn__text">{{ $slot }}</span>
  
  @if($icon && $iconPosition === 'right')
    <x-icon :name="$icon" size="sm" class="btn__icon" />
  @endif
</a>