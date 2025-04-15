@props([
  'heroClass',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="hero-class-name"
  :deleteConfirmValue="$heroClass->name"
  containerClass="hero-class-card"
  :title="$heroClass->name"
  :hasDetails="true"
>
  <x-slot:badge>
    <x-badge>
      {{ $heroClass->heroSuperclass ? $heroClass->heroSuperclass->name : 'Sin superclase' }}
    </x-badge>
  </x-slot:badge>

  <div class="card-summary">
    <div class="stat-item-grid">
      <x-stat-item icon="heroes" :count="$heroClass->heroes_count ?? 0" label="héroe" />
    </div>
  </div>
  
  <x-slot:details>
    @if($heroClass->passive)
      <x-description title="Pasiva">
        <div>{!! $heroClass->passive !!}</div>
      </x-description>
    @endif
    
    <div class="class-modifiers">
      <h4>Modificadores</h4>
      <x-attribute-modifiers-grid :modifiers="[
        'Agilidad' => $heroClass->agility_modifier,
        'Mental' => $heroClass->mental_modifier,
        'Voluntad' => $heroClass->will_modifier,
        'Fuerza' => $heroClass->strength_modifier,
        'Armadura' => $heroClass->armor_modifier
      ]" />
    </div>
  </x-slot:details>
</x-cards.entity-card>