@props(['block'])

@if($block->title)
  <h2 class="block__title" {{ $attributes->merge() }}>
    {{ $block->title }}
  </h2>
@endif

@if($block->subtitle)
  <h3 class="block__subtitle" {{ $attributes->merge() }}>
    {!! $block->subtitle !!}
  </h3>
@endif