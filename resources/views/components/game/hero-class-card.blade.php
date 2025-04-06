@props([
  'heroClass',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="hero-class-name"
  :deleteConfirmValue="$heroClass->name"
  containerClass="hero-class-card"
>
  <div class="hero-class-content">
    <div class="hero-class-header">
      <h3 class="hero-class-name">{{ $heroClass->name }}</h3>
      <span class="hero-class-superclass" 
            style="background-color: {{ $heroClass->superclass ? $heroClass->superclass->color : '#666666' }}; color: {{ $heroClass->superclass && $heroClass->superclass->text_is_dark ? '#000000' : '#ffffff' }}">
        {{ $heroClass->superclass ? $heroClass->superclass->name : 'Sin superclase' }}
      </span>
    </div>
    
    @if($heroClass->description)
      <div class="hero-class-description">
        <p>{{ $heroClass->description }}</p>
      </div>
    @endif
    
    @if($heroClass->passive)
      <div class="hero-class-passive">
        <h4>Pasiva</h4>
        <p>{{ $heroClass->passive }}</p>
      </div>
    @endif
    
    <div class="hero-class-modifiers">
      <h4>Modificadores</h4>
      <x-game.attribute-modifiers-grid :modifiers="[
        'Agilidad' => $heroClass->agility_modifier,
        'Mental' => $heroClass->mental_modifier,
        'Voluntad' => $heroClass->will_modifier,
        'Fuerza' => $heroClass->strength_modifier,
        'Armadura' => $heroClass->armor_modifier
      ]" />
    </div>
  </div>
</x-entity-card>