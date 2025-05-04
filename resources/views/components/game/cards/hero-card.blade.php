@props([
  'hero',
  'showRoute' => null,
  'editRoute' => null,
  'deleteRoute' => null
])

<x-game.cards.entity-card
  :borderColor="$hero->faction->color ?? null"
  :showRoute="$showRoute"
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="hero-name"
  :deleteConfirmValue="$hero->name"
  containerClass="hero-card"
  :title="$hero->name"
  :hasDetails="true"
>
  <x-slot:badge>
    @if($hero->image)
      <x-core.badge variant="icon">
        <img src="{{ asset('storage/' . $hero->image) }}" alt="{{ $hero->name }}">
      </x-core.badge>
    @else
      <x-core.badge variant="icon" :color="$hero->faction->color">
        {{ strtoupper(substr($hero->name, 0, 1)) }}
      </x-core.badge>
    @endif
  </x-slot:badge>

  <div class="card-summary">
    <x-core.info-item icon="heroes" label="{{ $hero->race->name . ' ' . $hero->heroClass->name }}" />
  </div>
  
  <x-slot:details>
    @if($hero->lore_text)
      <x-core.description>
        <p>{{ $hero->lore_text }}</p>
      </x-core.description>
    @endif
  </x-slot:details>
</x-game.cards.entity-card>