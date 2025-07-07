@props(['block'])

@if($block->title)
  <h2 class="block__title" {{ $attributes->merge() }}>
    {{ $block->title }}
  </h2>
@endif

@if($block->subtitle)
  <div class="block__subtitle" {{ $attributes->merge() }}>
    {!! $block->subtitle !!}
  </div>
@endif