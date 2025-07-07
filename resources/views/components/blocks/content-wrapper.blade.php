@props(['block'])

@php
  $hasImage = $block->image ? true : false;
  $imagePosition = $block->settings['image_position'] ?? 'top';
  $contentWrapperClass = 'block__content-wrapper';
  
  if ($hasImage) {
    $contentWrapperClass .= ' has-image-' . $imagePosition;
    
    // Scale mode para todas las posiciones de imagen
    $scaleMode = $block->settings['image_scale_mode'] ?? 'cover';
    $contentWrapperClass .= ' image-scale-' . $scaleMode;
    
    // Proportions para left/right Y clearfix
    if (in_array($imagePosition, ['left', 'right', 'clearfix-left', 'clearfix-right'])) {
      $proportions = $block->settings['column_proportions'] ?? '1-1';
      $contentWrapperClass .= ' proportions-' . $proportions;
    }
  }
@endphp

<div class="{{ $contentWrapperClass }}">
  {{ $slot }}
</div>