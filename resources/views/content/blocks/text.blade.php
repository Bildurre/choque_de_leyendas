<section class="block block--text" 
  @if($block->background_color) style="background-color: {{ $block->background_color }};" @endif
>
  <div class="block__inner @if($block->settings['full_width'] ?? false) block__inner--full-width @endif">
    @if($block->title)
      <h2 class="block__title text-{{ $block->settings['text_alignment'] ?? 'left' }}">{{ $block->title }}</h2>
    @endif
    
    @if($block->subtitle)
      <h3 class="block__subtitle text-{{ $block->settings['text_alignment'] ?? 'left' }}">{{ $block->subtitle }}</h3>
    @endif
    
    <div class="block__content text-{{ $block->settings['text_alignment'] ?? 'left' }}">{!! $block->content !!}</div>
  </div>
</section>