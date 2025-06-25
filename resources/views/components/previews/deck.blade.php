@props([
  'deck'
])

<article 
  class="deck-preview"
  style="--faction-color: {{ $deck->faction->color }}; --faction-color-rgb: {{ $deck->faction->rgb_values }}; --faction-text: {{ $deck->faction->text_is_dark ? '#000000' : '#ffffff' }}"
>
  <div class="deck-preview__content">
    <p class="deck-preview__mode">{{ $deck->gameMode->name }}</p>

    @if($deck->hasImage())
      <div class="deck-preview__icon">
        <img src="{{ $deck->getImageUrl() }}" alt="{{ $deck->name }}">
      </div>
    @elseif($deck->faction->hasImage())
      <div class="deck-preview__icon">
        <img src="{{ $deck->faction->getImageUrl() }}" alt="{{ $deck->name }}">
      </div>
    @endif
    
    <h3 class="deck-preview__name">{{ $deck->name }}</h3>
  </div>
</article>