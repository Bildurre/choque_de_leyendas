@props([
  'filled' => false,
  'route' => '#',
  'size' => 'md'
])

@php
  $buttonClass = "btn" .( $filled ? " btn__filled" : "");

  if ($size !== 'md') {
    $buttonClass .= " btn__{$size}";
  }
@endphp
  <a href={{ $route }} class="{{ $buttonClass }}" >
    {{ $slot }}
  </a>