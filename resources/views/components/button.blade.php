@props([
  'filled' => false,
  'route' => '#',
  'size' => 'md',
  'type' => null,
  'variant' => null
])

@php
  $buttonClass = "btn" .( $filled ? " btn--filled" : "");

  if ($size !== 'md') {
    $buttonClass .= " btn--{$size}";
  }

  if ($variant == 'success') {
    $buttonClass .= " btn--success";
  } else if ($variant == 'danger') {
    $buttonClass .= " btn--danger";
  }
@endphp

@if ($type == 'button')
  <button type="submit" class="{{ $buttonClass }}">{{ $slot }}</button>
@else
  <a href={{ $route }} class="{{ $buttonClass }}" >{{ $slot }}</a>
@endif