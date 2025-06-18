@props([
  'deck'
])

<div class="deck-preview">
  <div class="deck-preview__background">
    @if($deck->hasImage())
      <img src="{{ $deck->getImageUrl() }}" alt="{{ $deck->name }}" class="deck-preview__icon">
    @elseif($deck->faction->hasImage())
      <img src="{{ $deck->faction->getImageUrl() }}" alt="{{ $deck->faction->name }}" class="deck-preview__icon deck-preview__icon--faction">
    @endif
  </div>
  
  <div class="deck-preview__content">
    <h2 class="deck-preview__name">{{ $deck->name }}</h2>
    <p class="deck-preview__mode">{{ $deck->gameMode->name }}</p>
  </div>
  
  <div class="deck-preview__stats">
    <div class="deck-preview__stat">
      <span class="deck-preview__stat-value">{{ $deck->totalHeroes }}</span>
      <span class="deck-preview__stat-label">{{ __('entities.heroes.plural') }}</span>
    </div>
    <div class="deck-preview__stat">
      <span class="deck-preview__stat-value">{{ $deck->totalCards }}</span>
      <span class="deck-preview__stat-label">{{ __('entities.cards.plural') }}</span>
    </div>
  </div>
</div>