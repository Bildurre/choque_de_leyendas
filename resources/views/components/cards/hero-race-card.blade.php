<!-- resources/views/components/cards/hero-race-card.blade.php -->
@props([
  'heroRace',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="hero-race-name"
  :deleteConfirmValue="$heroRace->name"
  containerClass="hero-race-card"
  :title="$heroRace->name"
  :hasDetails="true"
>
  <div class="card-summary">
    <x-stat-item icon="heroes" :count="$heroRace->heroes_count ?? 0" label="héroe" />
  </div>
  
  <x-slot:details>
    <x-description wrapper>
      <x-description title="Agilidad:" row>{{ $heroRace->agility_modifier }}</x-description>
      <x-description title="Mente:" row>{{ $heroRace->mental_modifier }}</x-description>
      <x-description title="Voluntad:" row>{{ $heroRace->will_modifier }}</x-description>
      <x-description title="Fuerza:" row>{{ $heroRace->strength_modifier }}</x-description>
      <x-description title="Armadura:" row>{{ $heroRace->armor_modifier }}</x-description>
    </x-description>
  </x-slot:details>
</x-cards.entity-card>