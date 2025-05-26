@php
  // Obtener el contenido del CTA
  $ctaContent = $block->getTranslation('content', app()->getLocale());
  $ctaText = $ctaContent['text'] ?? '';
  $buttonText = $ctaContent['button_text'] ?? '';
  $buttonLink = $ctaContent['button_link'] ?? '';
  
  // Obtener la posición de la imagen
  $imagePosition = $block->settings['image_position'] ?? 'top';
  $hasImage = $block->image ? true : false;
  $imageClass = $hasImage ? 'has-image-' . $imagePosition : '';
@endphp

<section class="block block--cta" 
  @if($block->background_color && $block->background_color != 'none') 
    data-background="{{ $block->background_color }}"
  @endif
>
  <div class="block__inner @if($block->settings['full_width'] ?? false) block__inner--full-width @endif">
    <div class="cta-block">
      <div class="cta-block__card {{ $imageClass }}">
        @if($block->image && in_array($imagePosition, ['top', 'left']))
          <div class="cta-block__image">
            <img src="{{ $block->getImageUrl() }}" alt="{{ $block->title }}">
          </div>
        @endif
        
        <div class="cta-block__content text-{{ $block->settings['text_alignment'] ?? 'center' }}">
          @if($block->title)
            <h2 class="cta-block__title">{{ $block->title }}</h2>
          @endif
          
          @if($block->subtitle)
            <h3 class="cta-block__subtitle">{{ $block->subtitle }}</h3>
          @endif
          
          @if($ctaText)
            <div class="cta-block__text">{!! $ctaText !!}</div>
          @endif
          
          @if($buttonText && $buttonLink)
            <div class="cta-block__action">
              <x-button-link
                :href="$buttonLink"
                :variant="$block->settings['button_variant'] ?? 'primary'"
                :size="$block->settings['button_size'] ?? 'lg'"
              >
                {{ $buttonText }}
              </x-button-link>
            </div>
          @endif
        </div>
        
        @if($block->image && in_array($imagePosition, ['right', 'bottom']))
          <div class="cta-block__image">
            <img src="{{ $block->getImageUrl() }}" alt="{{ $block->title }}">
          </div>
        @endif
      </div>
    </div>
  </div>
</section>