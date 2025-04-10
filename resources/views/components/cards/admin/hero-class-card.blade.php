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
    <x-common.badge>
      {{ $heroClass->heroSuperclass ? $heroClass->heroSuperclass->name : 'Sin superclase' }}
    </x-common.badge>
  </x-slot:badge>

  <div class="card-summary">
    <div class="stat-item-grid">
      <x-common.stat-item icon="heroes" :count="$heroClass->heroes_count ?? 0" label="héroe" />
    </div>
  </div>
  
  <x-slot:details>
    @if($heroClass->passive)
      <x-common.description-section title="Pasiva">
        <div>{!! $heroClass->passive !!}</div>
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