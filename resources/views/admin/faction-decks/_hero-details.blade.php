<div class="hero-details">
  <div class="hero-details__attributes">
    <div class="hero-details__attribute">
      <span class="hero-details__attribute-label">{{ __('entities.heroes.attributes.agility') }}:</span>
      <span class="hero-details__attribute-value">{{ $entity->agility }}</span>
    </div>
    <div class="hero-details__attribute">
      <span class="hero-details__attribute-label">{{ __('entities.heroes.attributes.mental') }}:</span>
      <span class="hero-details__attribute-value">{{ $entity->mental }}</span>
    </div>
    <div class="hero-details__attribute">
      <span class="hero-details__attribute-label">{{ __('entities.heroes.attributes.will') }}:</span>
      <span class="hero-details__attribute-value">{{ $entity->will }}</span>
    </div>
    <div class="hero-details__attribute">
      <span class="hero-details__attribute-label">{{ __('entities.heroes.attributes.strength') }}:</span>
      <span class="hero-details__attribute-value">{{ $entity->strength }}</span>
    </div>
    <div class="hero-details__attribute">
      <span class="hero-details__attribute-label">{{ __('entities.heroes.attributes.armor') }}:</span>
      <span class="hero-details__attribute-value">{{ $entity->armor }}</span>
    </div>
    <div class="hero-details__attribute hero-details__attribute--total">
      <span class="hero-details__attribute-label">{{ __('entities.heroes.attributes.health') }}:</span>
      <span class="hero-details__attribute-value">{{ $entity->health }}</span>
    </div>
  </div>
  
  @if($entity->heroRace)
    <div class="hero-details__meta">
      <div class="hero-details__item">
        <span class="hero-details__label">{{ __('entities.hero_races.singular') }}:</span>
        <span class="hero-details__value">{{ $entity->heroRace->name }}</span>
      </div>
      
      @if($entity->gender)
        <div class="hero-details__item">
          <span class="hero-details__label">{{ __('entities.heroes.gender') }}:</span>
          <span class="hero-details__value">{{ __('entities.heroes.genders.' . $entity->gender) }}</span>
        </div>
      @endif
    </div>
  @endif
</div>