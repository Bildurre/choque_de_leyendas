@props([
  'card'
])

@php

  $class = $card->cardType->name;
  if ($card->cardType->id == 1) {
    $class .= ' • ' . __('entities.equipment_types.categories.' . $card->equipmentType->category) . ' • ' . $card->equipmentType->name;
    if ($card->equipmentType->category == 'weapon') {
      $class .= ' • ' . $card->hands . ' ' . ($card->hands > 1 ? __('entities.cards.hands') : __('entities.cards.hand'));
    }
  } elseif ($card->cardType->heroSuperclass != null) {
    $class .= $card->cardSubtype ? ' • ' . $card->cardSubtype->name : '';
    $class .= ' • ' . $card->cardType->heroSuperclass->name;
  }

  $types = '';
  $typeParts = [];

  if ($card->attackRange) {
    $typeParts[] = $card->attackRange->name;
  }

  if ($card->attack_type) {
    $typeParts[] = __('entities.cards.attack_types.' . $card->attack_type);
  }

  if ($card->attackSubtype) {
    $typeParts[] = $card->attackSubtype->name;
  }

  if ($card->area) {
    $typeParts[] = __('entities.cards.area');
  }

  if (!empty($typeParts)) {
    $types = implode(' • ', $typeParts);
  }
  
@endphp

<x-previews.entity :entity="$card" type="card">
  <x-slot:header>
    <h2 class="entity-preview__name">{{ $card->name }}</h2>
    <h3 class="entity-preview__class">{{ $class }}</h3>
  </x-slot:header>
  
  <x-slot:attributes_section>
    <section class="card-preview__cost">
      <x-cost-display :cost="$card->cost" />
    </section>
  </x-slot:attributes_section>

  <x-slot:abilities>
    <div class="card-preview__info">
      @if ($card->is_unique)
        <span class="card-preview__unique"> {{ __('admin.is_unique') }} </span>
      @endif
      @if ($card->attackSubtype && $card->attackRange)
        <span class="card-preview__types"> {{ $types }} </span>
      @endif
    </div>
    <div class="card-preview__effects">
      @if ($card->restriction)
        <div class="card-preview__effects-restrictions">{!! $card->restriction !!}</div>
        <hr />
      @endif
      @if ($card->effect)
        <div class="card-preview__effects-effects">{!! $card->effect !!}</div>
      @endif
    </div>
    @if ($card->heroAbility)
      <hr />
      <div class="entity-preview__active">
        <div class="entity-preview__active-header">
          <div class="entity-preview__active-info">
            <span class="entity-preview__active-name">{{ $card->heroAbility->name }}</span>
            <span class="entity-preview__active-types">
              {{ $card->heroAbility->attackRange->name . 
                ' • ' . __('entities.attack_subtypes.types.'.$card->heroAbility->attack_type) . 
                ' • ' . $card->heroAbility->attackSubtype->name . 
                ($card->heroAbility->area ? ' • '.__('entities.hero_abilities.area') : '')
              }}
            </span>
          </div>
          <div class="entity-preview__active-cost">
            <x-cost-display :cost="$card->heroAbility->cost" />
          </div>
        </div>
        <div class="entity-preview__active-description">
          {!! $card->heroAbility->description !!}
        </div>
      </div>
    @endif
  </x-slot:abilities>
</x-previews.entity>