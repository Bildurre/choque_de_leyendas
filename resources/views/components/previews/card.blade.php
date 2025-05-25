@props([
  'card'
])

@php
  $class = $card->cardType->name;
  if ($card->cardType->id == 1) {
    $class .= ' - ' . $card->equipmentType->name;
    if ($card->equipmentType->category == 'weapon') {
      $class .= ' - ' . $card->hands . ' ' . ($card->hands > 1 ? __('entities.cards.hands') : __('entities.cards.hand'));
    }
  } elseif ($card->cardType->heroSuperclass != null) {
    $class .= ' - ' . $card->cardType->heroSuperclass->name;
  }

  $types = '';
  if ($card->attackRange && $card->attackSubtype) {
    $types = $card->attackRange->name . 
              ' - ' . __('entities.attack_subtypes.types.' . $card->attackSubtype->type) . 
              ' - ' . $card->attackSubtype->name . 
              ($card->area ? ' - '.__('entities.hero_abilities.area') : '');
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
    <div class="card-preview__types">
      @if ($card->attackSubtype && $card->attackRange)
        <span> {{ $types }} </span>
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
                ' - ' . __('entities.attack_subtypes.types.'.$card->heroAbility->attackSubtype->type) . 
                ' - ' . $card->heroAbility->attackSubtype->name . 
                ($card->heroAbility->area ? ' - '.__('entities.hero_abilities.area') : '')
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