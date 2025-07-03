@props(['block'])

@if($block->image)
  <div class="block__image" {{ $attributes }}>
    <img 
      src="{{ $block->getImageUrl() }}" 
      alt="{{ $block->title ?: 'Block image' }}"
      loading="lazy"
    >
  </div>
@endif