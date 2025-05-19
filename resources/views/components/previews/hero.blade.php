@props([
  'hero'
])

<article 
  class="hero-preview"
  style="--faction-color: {{ $hero->faction->color }}; --faction-color-rgb: {{ $hero->faction->rgb_values }}; --faction-text: {{ $hero->faction->text_is_dark ? '#000000' : '#ffffff' }}"
>
  <header class="hero-preview__header">
    <div class="hero-preview__title-container">
      <h2 class="hero-preview__name">{{ $hero->name }}</h2>
      <h3 class="hero-preview__class">{{ $hero->getGenderizedRaceName() . ' - ' . $hero->getGenderizedClassName() . ' - ' . $hero->getGenderizedSuperclassName() }}</h3>
    </div>
    <div class="hero-preview__faction-logo">
      <img src="{{ $hero->faction->getImageUrl() }}" alt="{{ $hero->faction->name }}">
    </div>
  </header>

  <div class="hero-preview__image-container">
    <img class="hero-preview__image" src="{{ $hero->getImageUrl() }}" alt="{{ $hero->name }}">
  </div>
  
  <section class="hero-preview__attributes">
    <div class="hero-preview__attribute">
      <x-icon-attribute type="agility" />
      <span class="hero-preview__attribute-value">{{ $hero->agility }}</span>
    </div>
    <div class="hero-preview__attribute">
      <x-icon-attribute type="mental" />
      <span class="hero-preview__attribute-value">{{ $hero->mental }}</span>
    </div>
    <div class="hero-preview__attribute">
      <x-icon-attribute type="will" />
      <span class="hero-preview__attribute-value">{{ $hero->will }}</span>
    </div>
    <div class="hero-preview__attribute">
      <x-icon-attribute type="strength" />
      <span class="hero-preview__attribute-value">{{ $hero->strength }}</span>
    </div>
    <div class="hero-preview__attribute">
      <x-icon-attribute type="armor" />
      <span class="hero-preview__attribute-value">{{ $hero->armor }}</span>
    </div>
    <div class="hero-preview__attribute">
      <x-icon-attribute type="health" />
      <span class="hero-preview__attribute-value">{{ $hero->health }}</span>
    </div>
  </section>

  <section class="hero-preview__abilities">
    <div class="hero-preview__passives">
      <div class="hero-preview__passive">
        <span class="hero-preview__passive-name">{{ $hero->heroClass->name }}:</span>
        {!! $hero->heroClass->passive !!}
      </div>
      <div class="hero-preview__passive">
        <span class="hero-preview__passive-name">
          {{ $hero->passive_name }}:
        </span>
        {!! $hero->passive_description !!}
      </div>
    </div>

    <hr />
    
    <div class="hero-preview__actives">
      @foreach ($hero->heroAbilities as $ability)
        <div class="hero-preview__active">
          <div class="hero-preview__active-header">
            <div class="hero-preview__active-info">
              <span class="hero-preview__active-name">{{ $ability->name }}</span>
              <span class="hero-preview__active-types">
                {{ $ability->attackRange->name . 
                  ' - ' . __('entities.attack_subtypes.types.'.$ability->attackSubtype->type) . 
                  ' - ' . $ability->attackSubtype->name . 
                  ($ability->area ? ' - '.__('entities.hero_abilities.area') : '')
                }}
              </span>
            </div>
            <div class="hero-preview__active-cost">
              <x-cost-display :cost="$ability->cost" />
            </div>
          </div>
          <div class="hero-preview__active-description">
            {!! $ability->description !!}
          </div>
        </div>
        @if (!$loop->last)
          <hr />
        @endif
      @endforeach
    </div>
  </section>
  <footer class="hero-preview__footer">
    <span>Alanda: Choque de Leyendas</span>
    <x-logo-icon />
  </footer>
</article>