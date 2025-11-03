@props([
  'deck'
])

@php
  $colorConfig = $deck->getColorConfiguration();
  $isMultiFaction = $colorConfig['is_multi_faction'];
  $primaryFaction = $deck->getPrimaryFaction();

  $colors = $colorConfig['colors'] ?? [];
  $lastIndex = max(count($colors) - 1, 0);

  $leftColor = $colors[0] ?? '#000000';
  $rightColor = $colors[$lastIndex] ?? $leftColor;

  $textColors = $colorConfig['text_colors'] ?? [];
  $leftTextColor = $textColors[0] ?? ($colorConfig['text_color'] ?? '#ffffff');
  $rightTextColor = $textColors[$lastIndex] ?? ($colorConfig['text_color'] ?? '#ffffff');

  // Vars para modo (primer color del gradiente)
  $modeBg = $leftColor;
  $modeText = $leftTextColor;

  // Badge:
  // - Mono-facción: color de facción
  // - Multi-facción: último color del gradiente
  $badgeBg = $isMultiFaction ? $rightColor : $leftColor;
  $badgeText = $isMultiFaction ? $rightTextColor : $leftTextColor;
@endphp


<article 
  class="deck-preview {{ $isMultiFaction ? 'deck-preview--multi-faction' : '' }}"
  style="
    --faction-color: {{ $isMultiFaction ? 'transparent' : $leftColor }}; 
    --faction-gradient: {{ $deck->getGradientCss() }}; 
    --faction-text: {{ $colorConfig['text_color'] }};
    --faction-color-left: {{ $leftColor }};
    --faction-color-right: {{ $rightColor }};
    --faction-text-left: {{ $leftTextColor }};
    --faction-text-right: {{ $rightTextColor }};
    --mode-bg: {{ $modeBg }};
    --mode-text: {{ $modeText }};
    --badge-bg: {{ $badgeBg }};
    --badge-text: {{ $badgeText }};
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
