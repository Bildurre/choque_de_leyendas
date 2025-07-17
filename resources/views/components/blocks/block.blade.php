@props(['block'])

@php
  // Configuración común para todos los bloques
  $blockType = $block->type;
  $textAlignment = $block->settings['text_alignment'] ?? 'left';
  $fullWidth = $block->settings['full_width'] ?? false;
  $backgroundColor = $block->background_color ?? 'none';
  
  // Clases para el inner container
  $innerClass = 'block__inner';
  if ($fullWidth) {
    $innerClass .= ' block__inner--full-width';
  }

  // controlar el tamaño de la imagen
  $limitHeight = $block->settings['limit_height'] ?? false;
@endphp

<section 
  id="block-{{ $block->order }}"
  class="block block--{{ $blockType }} text-{{ $textAlignment }} {{ (!$limitHeight && $block->hasMultilingualImage()) ? 'no-height-limit' : '' }}" 
  @if($backgroundColor !== 'none') 
    data-background="{{ $backgroundColor }}"
  @endif
  {{ $attributes }}
>
  <div class="{{ $innerClass }}">
    {{ $slot }}
  </div>
</section>