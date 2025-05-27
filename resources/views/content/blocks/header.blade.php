<section class="block block--header" 
  @if($block->background_color && $block->background_color != 'none') 
    data-background="{{ $block->background_color }}"
  @endif
>
  <div class="block__inner">
    <div class="header-block__content text-{{ $block->settings['text_alignment'] ?? 'center' }}">
      @if($block->title)
        <h2 class="block__title">{{ $block->title }}</h2>
      @endif
      
      @if($block->subtitle)
        <h3 class="block__subtitle">{{ $block->subtitle }}</h3>
      @endif
    </div>
  </div>
</section>