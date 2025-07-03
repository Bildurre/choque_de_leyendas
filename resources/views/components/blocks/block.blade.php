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
@endphp

<section 
  class="block block--{{ $blockType }} text-{{ $textAlignment }}" 
  @if($backgroundColor !== 'none') 
    data-background="{{ $backgroundColor }}"
  @endif
  {{ $attributes }}
>
  <div class="{{ $innerClass }}">
    {{ $slot }}
  </div>
</section>