@props([
  'deck'
])

@php
  $colorConfig = $deck->getColorConfiguration();
  $isMultiFaction = $colorConfig['is_multi_faction'];
  $primaryFaction = $deck->getPrimaryFaction();
@endphp

<article 
  class="deck-preview {{ $isMultiFaction ? 'deck-preview--multi-faction' : '' }}"
  style="
    --faction-color: {{ $isMultiFaction ? 'transparent' : $colorConfig['colors'][0] }}; 
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
    
    <span class="deck-preview__badge">
      @if($isMultiFaction)
        {{ __('entities.faction_decks.multi_faction') }}
      @elseif($primaryFaction)
        {{ $primaryFaction->name }}
      @endif
    </span>
  </div>
</article>