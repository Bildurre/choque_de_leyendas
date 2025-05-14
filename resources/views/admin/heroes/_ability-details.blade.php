<div class="ability-details">
  @if($entity->attackRange)
    <div class="ability-details__item">
      <span class="ability-details__label">{{ __('entities.attack_ranges.singular') }}:</span>
      <span class="ability-details__value">{{ $entity->attackRange->name }}</span>
    </div>
  @endif
  
  @if($entity->attackSubtype)
    <div class="ability-details__item">
      <span class="ability-details__label">{{ __('entities.attack_subtypes.singular') }}:</span>
      <span class="ability-details__value {{ $entity->attackSubtype->type }}">
        {{ $entity->attackSubtype->name }}
      </span>
    </div>
  @endif
  
  @if($entity->area)
    <div class="ability-details__item">
      <span class="ability-details__label">{{ __('entities.hero_abilities.type') }}:</span>
      <span class="ability-details__value">
        {{ __('entities.hero_abilities.area') }}
      </span>
    </div>
  @endif
  
  @if($entity->cost)
    <div class="ability-details__cost">
      <x-cost-display :cost="$entity->cost" />
    </div>
  @endif
</div>