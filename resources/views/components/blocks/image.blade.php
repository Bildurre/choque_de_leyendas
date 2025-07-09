@props(['block'])

@if($block->hasMultilingualImage())
  <div class="block__image" {{ $attributes }}>
    <img 
      src="{{ $block->getMultilingualImageUrl() }}" 
      alt="{{ $block->title ?: 'Block image' }}"
      loading="lazy"
    >
  </div>
@endif