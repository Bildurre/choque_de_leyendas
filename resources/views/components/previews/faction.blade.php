@props([
  'faction'
])

<article 
  class="faction-preview"
  style="--faction-color: {{ $faction->color }}; --faction-color-rgb: {{ $faction->rgb_values }}; --faction-text: {{ $faction->text_is_dark ? '#000000' : '#ffffff' }}"
>
  <div class="faction-preview__content">
    @if($faction->hasImage())
      <div class="faction-preview__icon">
        <img src="{{ $faction->getImageUrl() }}" alt="{{ $faction->name }} icon">
      </div>
    @endif
    
    <h3 class="faction-preview__name">{{ $faction->name }}</h3>
  </div>
</article>