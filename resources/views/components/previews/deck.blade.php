@props([
  'deck'
])

@php
  $colorConfig = $deck->getColorConfiguration();
@endphp

<article 
  class="deck-preview {{ $colorConfig['is_multi_faction'] ? 'deck-preview--multi-faction' : '' }}"
  style="
    --faction-color: {{ $colorConfig['is_multi_faction'] ? 'transparent' : $colorConfig['colors'][0] }}; 
    --faction-gradient: {{ $deck->getGradientCss() }}; 
    --faction-text: {{ $colorConfig['text_color'] }};
  "
>
  <div class="deck-preview__content">
    <p class="deck-preview__mode">{{ $deck->gameMode->name }}</p>

    @if($deck->hasImage())
      <div class="deck-preview__icon">
        <img src="{{ $deck->getImageUrl() }}" alt="{{ $deck->name }}">
      </div>
    @endif
    
    <h3 class="deck-preview__name">{{ $deck->name }}</h3>
    
    @if($colorConfig['is_multi_faction'])
      <span class="deck-preview__badge">{{ __('entities.faction_decks.multi_faction') }}</span>
    @endif
  </div>
</article>