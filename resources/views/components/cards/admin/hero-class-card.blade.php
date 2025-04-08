@props([
  'heroClass',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.admin.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="hero-class-name"
  :deleteConfirmValue="$heroClass->name"
  containerClass="hero-class-card"
  :title="$heroClass->name"
  :hasDetails="true"
>
  <x-slot:badge>
    <x-common.badge 
      :color="$heroClass->superclass ? $heroClass->superclass->color : '#666666'" 
      :textColor="$heroClass->superclass && $heroClass->superclass->text_is_dark ? '#000000' : '#ffffff'"
    >
      {{ $heroClass->superclass ? $heroClass->superclass->name : 'Sin superclase' }}
    </x-common.badge>
  </x-slot:badge>

  <div class="hero-class-summary">
    <div class="class-stats">
      <x-common.stat-item icon="heroes" :count="$heroClass->heroes_count ?? 0" label="héroe" />
    </div>
  </div>
  
  <x-slot:details>
    @if($heroClass->description)
      <x-common.description-section title="Descripción">
        <p>{{ $heroClass->description }}</p>
      </x-common.description-section>
    @endif

    @if($heroClass->passive)
      <x-common.description-section title="Pasiva">
        <p>{{ $heroClass->passive }}</p>
      </x-common.description-section>
    @endif
    
    <div class="class-modifiers">
      <h4>Modificadores</h4>
      <x-widgets.attribute-modifiers-grid :modifiers="[
        'Agilidad' => $heroClass->agility_modifier,
        'Mental' => $heroClass->mental_modifier,
        'Voluntad' => $heroClass->will_modifier,
        'Fuerza' => $heroClass->strength_modifier,
        'Armadura' => $heroClass->armor_modifier
      ]" />
    </div>
  </x-slot:details>
</x-cards.admin.entity-card>