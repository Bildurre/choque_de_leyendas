@props([
  'heroClass',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-game.cards.entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="hero-class-name"
  :deleteConfirmValue="$heroClass->name"
  containerClass="hero-class-card"
  :title="$heroClass->name"
  :hasDetails="true"
>
  <x-slot:badge>
    <x-core.badge>
      {{ $heroClass->heroSuperclass->name }}
    </x-core.badge>
  </x-slot:badge>

  <div class="card-summary">
    <x-core.stat-item icon="heroes" :count="$heroClass->heroes_count ?? 0" label="héroe" />
  </div>
  
  <x-slot:details>
    @if($heroClass->passive)
      <x-core.description title="Pasiva:">
        <div>{!! $heroClass->passive !!}</div>
      </x-core.description>
    @endif
  </x-slot:details>
</x-game.cards.entity-card>