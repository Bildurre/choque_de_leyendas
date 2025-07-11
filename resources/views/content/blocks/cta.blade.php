@php
  $ctaContent = $block->getTranslation('content', app()->getLocale());
  $ctaText = $ctaContent['text'] ?? '';
  $buttonText = $ctaContent['button_text'] ?? '';
  $buttonLink = $ctaContent['button_link'] ?? '';
  $imagePosition = $block->settings['image_position'] ?? 'top';
  $cardWidth = $block->settings['width'] ?? 'lg';
@endphp

<x-blocks.block :block="$block">
  <div class="cta-block">
    <div class="cta-block__card {{ 'cta-block__card--'.$cardWidth }} {{  $block->hasMultilingualImage() ?: 'cta-block__card--no-image' }}">
      <x-blocks.content-wrapper :block="$block">
        @if($block->hasMultilingualImage() && in_array($imagePosition, ['top', 'left']))
          <x-blocks.image :block="$block" />
        @endif
        
        <div class="block__content">
          <x-blocks.titles :block="$block" />
          
          @if($ctaText)
            <div class="block__text">{!! $ctaText !!}</div>
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
        
        @if($block->hasMultilingualImage() && in_array($imagePosition, ['bottom', 'right']))
          <x-blocks.image :block="$block" />
        @endif
      </x-blocks.content-wrapper>
    </div>
  </div>
</x-blocks.block>