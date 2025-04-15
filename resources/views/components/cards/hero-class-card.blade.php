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
      {{ $heroClass->heroSuperclass->name }}
    </x-badge>
  </x-slot:badge>

  <div class="card-summary">
    <x-stat-item icon="heroes" :count="$heroClass->heroes_count ?? 0" label="héroe" />
  </div>
  
  <x-slot:details>
    @if($heroClass->passive)
      <x-description title="Pasiva:">
        <div>{!! $heroClass->passive !!}</div>
      </x-description>
    @endif

    <x-description wrapper>
      <x-description title="Agilidad:" row>{{ $heroClass->agility_modifier }}</x-description>
      <x-description title="Mente:" row>{{ $heroClass->mental_modifier }}</x-description>
      <x-description title="Voluntad:" row>{{ $heroClass->will_modifier }}</x-description>
      <x-description title="Fuerza:" row>{{ $heroClass->strength_modifier }}</x-description>
      <x-description title="Armadura:" row>{{ $heroClass->armor_modifier }}</x-description>
    </x-description>
    
  </x-slot:details>
</x-cards.entity-card>