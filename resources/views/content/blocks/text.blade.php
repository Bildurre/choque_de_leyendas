<section class="block block--text" 
  @if($block->background_color && $block->background_color != 'none') 
    data-background="{{ $block->background_color }}"
  @endif
>
  @php
    $imagePosition = $block->settings['image_position'] ?? 'top';
    $hasImage = $block->image ? true : false;
    $contentWrapperClass = $hasImage ? 'has-image-' . $imagePosition : '';
  @endphp
  
  <div class="block__inner @if($block->settings['full_width'] ?? false) block__inner--full-width @endif">
    <div class="block__content-wrapper {{ $contentWrapperClass }}">
      @if($block->image && in_array($imagePosition, ['top', 'left']))
        <div class="block__image">
          <img src="{{ $block->getImageUrl() }}" alt="{{ $block->title }}">
        </div>
      @endif
      
      <div class="block__content-column">
        @if($block->title)
          <h2 class="block__title text-{{ $block->settings['text_alignment'] ?? 'left' }}">{{ $block->title }}</h2>
        @endif
        
        @if($block->subtitle)
          <h3 class="block__subtitle text-{{ $block->settings['text_alignment'] ?? 'left' }}">{{ $block->subtitle }}</h3>
        @endif
        
        <div class="block__content text-{{ $block->settings['text_alignment'] ?? 'left' }}">{!! $block->content !!}</div>
      </div>
      
      @if($block->image && in_array($imagePosition, ['right', 'bottom']))
        <div class="block__image">
          <img src="{{ $block->getImageUrl() }}" alt="{{ $block->title }}">
        </div>
      @endif
    </div>
  </div>
</section>