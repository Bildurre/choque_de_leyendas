<section class="block block--header" 
  @if($block->background_image) 
    style="background-image: url('{{ $block->getBackgroundImageUrl() }}');"
  @elseif($block->background_color) 
    style="background-color: {{ $block->background_color }};"
  @endif
>
  <div class="block__inner">
    <div class="header-block__content text-{{ $block->settings['text_alignment'] ?? 'center' }}">
      @if($block->title)
        <h2 class="header-block__title">{{ $block->title }}</h2>
      @endif
      
      @if($block->subtitle)
        <h3 class="header-block__subtitle">{{ $block->subtitle }}</h3>
      @endif
    </div>
  </div>
</section>