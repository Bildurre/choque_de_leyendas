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
  <div class="hero-class-header">
    <h3 class="hero-class-name">{{ $heroClass->name }}</h3>
    <span class="hero-class-superclass">
      {{ $heroClass->superclass ? $heroClass->superclass->name : 'Sin superclase' }}
    </span>
  </div>
  
  <div class="hero-class-modifiers">
    <h4>Modificadores de Atributos</h4>
    <x-game.attribute-modifiers-grid :modifiers="[
      'Agilidad' => $heroClass->agility_modifier,
      'Mental' => $heroClass->mental_modifier,
      'Voluntad' => $heroClass->will_modifier,
      'Fuerza' => $heroClass->strength_modifier,
      'Armadura' => $heroClass->armor_modifier
    ]" />
  </div>
  
  @if($heroClass->passive)
    <div class="hero-class-passive">
      <h4>Pasiva</h4>
      <p>{{ $heroClass->passive }}</p>
    </div>
  @endif
</x-entity-card>