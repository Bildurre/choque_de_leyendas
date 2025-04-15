@props([
  'text' => null
])

@if($text)
  <p class="help-text">{{ $text }}</p>
@endif