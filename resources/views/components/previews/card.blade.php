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
@endphp

<article 
  class="card-preview"
  style="--faction-color: {{ $card->faction->color }}; --faction-color-rgb: {{ $card->faction->rgb_values }}; --faction-text: {{ $card->faction->text_is_dark ? '#000000' : '#ffffff' }}"
>
  <header class="card-preview__header">
    <div class="card-preview__title-container">
      <h2 class="card-preview__name">{{ $card->name }}</h2>
      <h3 class="card-preview__class">{{ $class }}</h3>
    </div>
    <div class="card-preview__faction-logo">
      <img src="{{ $card->faction->getImageUrl() }}" alt="{{ $card->faction->name }}">
    </div>
  </header>

  <div class="card-preview__image-container">
    <img class="card-preview__image" src="{{ $card->getImageUrl() }}" alt="{{ $card->name }}">
  </div>
  
  <section class="card-preview__cost">
    <x-cost-display :cost="$card->cost" />
  </section>

  <section class="card-preview__abilities">
    <div class="card-preview__types">
      @if ($card->attackSubtype && $card->attackRange)
        <span>
          {{ $card->attackRange->name . 
          ' - ' . __('entities.attack_subtypes.types.' . $card->attackSubtype->type) . 
          ' - ' . $card->attackSubtype->name . 
          ($card->area ? ' - '.__('entities.hero_abilities.area') : '')
          }}
        </span>
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
      <div class="card-preview__active">
        <div class="card-preview__active-header">
          <div class="card-preview__active-info">
            <span class="card-preview__active-name">{{ $card->heroAbility->name }}</span>
            <span class="card-preview__active-types">
              {{ $card->heroAbility->attackRange->name . 
                ' - ' . __('entities.attack_subtypes.types.'.$card->heroAbility->attackSubtype->type) . 
                ' - ' . $card->heroAbility->attackSubtype->name . 
                ($card->heroAbility->area ? ' - '.__('entities.hero_abilities.area') : '')
              }}
            </span>
          </div>
          <div class="card-preview__active-cost">
            <x-cost-display :cost="$card->heroAbility->cost" />
          </div>
        </div>
        <div class="card-preview__active-description">
          {!! $card->heroAbility->description !!}
        </div>
      </div>
    @endif
  </section>
  <footer class="card-preview__footer">
    <span>Alanda: Choque de Leyendas</span>
    <x-logo-icon />
  </footer>
</article>