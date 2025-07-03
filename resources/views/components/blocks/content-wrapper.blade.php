@props(['block'])

@php
  $hasImage = $block->image ? true : false;
  $imagePosition = $block->settings['image_position'] ?? 'top';
  $contentWrapperClass = 'block__content-wrapper';
  
  if ($hasImage) {
    $contentWrapperClass .= ' has-image-' . $imagePosition;
    
    if (in_array($imagePosition, ['left', 'right'])) {
      $scaleMode = $block->settings['image_scale_mode'] ?? 'adjust';
      $contentWrapperClass .= ' image-scale-' . $scaleMode;
      
      $proportions = $block->settings['column_proportions'] ?? '1-1';
      $contentWrapperClass .= ' proportions-' . $proportions;
    }
  }
@endphp

<div class="{{ $contentWrapperClass }}">
  {{ $slot }}
</div>