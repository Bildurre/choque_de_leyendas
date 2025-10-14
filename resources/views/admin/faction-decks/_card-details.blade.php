<div class="card-details">

    @if($entity->cost)
      <div class="card-details__cost">
        <x-cost-display :cost="$entity->cost" />
      </div>
    @endif

    @if($entity->equipmentType)
      <span class="card-details__meta">{{ $entity->equipmentType->name }}</span>
    @endif
    
    @if($entity->attackRange && $entity->attackSubtype)
      <span class="card-details__meta">
        {{ __('entities.attack_subtypes.types.'. $entity->attack_type) .' - '. $entity->attackRange->name .' - '. $entity->attackSubtype->name . ($entity->area ? ' - '. __('entities.cards.area') : '') }}
      </span>
    @endif


  @if($entity->effect)
    <div class="card-details__effect">{{ mb_strimwidth(strip_tags($entity->restriction). ' ' .strip_tags($entity->effect), 0, 150, '...') }}</div>
  @endif
  
  
</div>