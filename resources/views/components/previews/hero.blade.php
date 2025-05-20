@props([
  'hero'
])

<x-previews.entity :entity="$hero" type="hero">
  <x-slot:header>
    <h2 class="entity-preview__name">{{ $hero->name }}</h2>
    <h3 class="entity-preview__class">{{ $hero->getGenderizedRaceName() . ' - ' . $hero->getGenderizedClassName() . ' - ' . $hero->getGenderizedSuperclassName() }}</h3>
  </x-slot:header>
  
  <x-slot:attributes_section>
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
  </x-slot:attributes_section>

  <x-slot:abilities>
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
        <div class="entity-preview__active">
          <div class="entity-preview__active-header">
            <div class="entity-preview__active-info">
              <span class="entity-preview__active-name">{{ $ability->name }}</span>
              <span class="entity-preview__active-types">
                {{ $ability->attackRange->name . 
                  ' - ' . __('entities.attack_subtypes.types.'.$ability->attackSubtype->type) . 
                  ' - ' . $ability->attackSubtype->name . 
                  ($ability->area ? ' - '.__('entities.hero_abilities.area') : '')
                }}
              </span>
            </div>
            <div class="entity-preview__active-cost">
              <x-cost-display :cost="$ability->cost" />
            </div>
          </div>
          <div class="entity-preview__active-description">
            {!! $ability->description !!}
          </div>
        </div>
        @if (!$loop->last)
          <hr />
        @endif
      @endforeach
    </div>
  </x-slot:abilities>
</x-previews.entity>