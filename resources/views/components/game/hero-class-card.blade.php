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
  :title="$heroClass->name"
  :hasDetails="true"
>
  <x-slot:badge>
    <span class="hero-class-superclass" 
        style="background-color: {{ $heroClass->superclass ? $heroClass->superclass->color : '#666666' }}; color: {{ $heroClass->superclass && $heroClass->superclass->text_is_dark ? '#000000' : '#ffffff' }}">
      {{ $heroClass->superclass ? $heroClass->superclass->name : 'Sin superclase' }}
    </span>
  </x-slot:badge>

  <div class="hero-class-summary">
    <div class="class-stats">
      <div class="stat-item">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
        <span>{{ $heroClass->heroes_count ?? 0 }} {{ Str::plural('héroe', $heroClass->heroes_count ?? 0) }}</span>
      </div>
    </div>
  </div>
  
  <x-slot:details>
    @if($heroClass->description)
      <div class="class-description">
        <h4>Descripción</h4>
        <p>{{ $heroClass->description }}</p>
      </div>
    @endif
    
    @if($heroClass->passive)
      <div class="class-passive">
        <h4>Pasiva</h4>
        <p>{{ $heroClass->passive }}</p>
      </div>
    @endif
    
    <div class="class-modifiers">
      <h4>Modificadores</h4>
      <x-game.attribute-modifiers-grid :modifiers="[
        'Agilidad' => $heroClass->agility_modifier,
        'Mental' => $heroClass->mental_modifier,
        'Voluntad' => $heroClass->will_modifier,
        'Fuerza' => $heroClass->strength_modifier,
        'Armadura' => $heroClass->armor_modifier
      ]" />
    </div>
  </x-slot:details>
</x-entity-card>