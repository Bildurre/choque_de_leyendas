<section class="block block--text {{ $block->image ? 'has-image' : '' }} {{ $block->image && in_array(($block->settings['image_position'] ?? 'left'), ['left', 'right']) ? 'has-image-beside' : '' }}" 
  @if($block->background_color && $block->background_color != 'none') 
    data-background="{{ $block->background_color }}"
  @endif
>
  <div class="block__inner @if($block->settings['full_width'] ?? false) block__inner--full-width @endif">
    <!-- Eliminar la comprobación para 'top' ya que no es una opción en la configuración actual -->
    <div class="block__content-wrapper {{ $block->image ? 'has-image-' . ($block->settings['image_position'] ?? 'left') : '' }}">
      @if($block->image && ($block->settings['image_position'] ?? 'left') == 'left')
        <div class="block__image block__image--left">
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
      
      @if($block->image && ($block->settings['image_position'] ?? 'left') == 'right')
        <div class="block__image block__image--right">
          <img src="{{ $block->getImageUrl() }}" alt="{{ $block->title }}">
        </div>
      @endif
    </div>
  </div>
</section>