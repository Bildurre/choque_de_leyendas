@props(['block'])

@if ($block->title || (isset($actions) && $actions))
  <div class="block-header">
      @if($block->title)
        <h2 class="block__title" {{ $attributes->merge() }}>
          {{ $block->title }}
        </h2>
      @endif
  
      @if(isset($actions) && $actions)
        <div class="block-header__actions">
          {{ $actions }}
        </div>
      @endif
  </div>
@endif

@if($block->subtitle)
  <div class="block__subtitle" {{ $attributes->merge() }}>
    {!! $block->subtitle !!}
  </div>
@endif
