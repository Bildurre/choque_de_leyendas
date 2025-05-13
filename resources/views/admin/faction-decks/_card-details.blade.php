<div class="card-details">
  @if($entity->effect)
    <div class="card-details__item card-details__effect">
      <div class="card-details__effect-text">{{ strip_tags($entity->effect) }}</div>
    </div>
  @endif
  
  <div class="card-details__meta">
    @if($entity->cost)
      <div class="card-details__cost">
        <x-cost-display :cost="$entity->cost" />
      </div>
    @endif
    
    @if($entity->equipmentType)
      <div class="card-details__item">
        <span class="card-details__label">{{ __('equipment_types.singular') }}:</span>
        <span class="card-details__value">{{ $entity->equipmentType->name }}</span>
      </div>
    @endif
    
    @if($entity->attackRange)
      <div class="card-details__item">
        <span class="card-details__label">{{ __('attack_ranges.singular') }}:</span>
        <span class="card-details__value">{{ $entity->attackRange->name }}</span>
      </div>
    @endif
    
    @if($entity->attackSubtype)
      <div class="card-details__item">
        <span class="card-details__label">{{ __('attack_subtypes.singular') }}:</span>
        <span class="card-details__value {{ $entity->attackSubtype->type }}">
          {{ $entity->attackSubtype->name }}
        </span>
      </div>
    @endif
    
    @if(isset($entity->area) && $entity->area)
      <div class="card-details__item">
        <span class="card-details__label">{{ __('cards.type') }}:</span>
        <span class="card-details__value">
          {{ __('cards.area') }}
        </span>
      </div>
    @endif
  </div>
</div>