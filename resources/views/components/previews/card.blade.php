@props([
  'card' => null
])


<div 
  class="preview card-preview"
  style="--color-faction = {{ $card->faction->color }};"
>
  <div class="preview__header">
    <div class="preview__title">

    </div>
    <div class="faction-logo">

    </div>
  </div>
  
</div>