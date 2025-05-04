@props([
  'hero',
  'showRoute' => null,
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.entity-card
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
      <x-badge variant="icon">
        <img src="{{ asset('storage/' . $hero->image) }}" alt="{{ $hero->name }}">
      </x-badge>
    @else
      <x-badge variant="icon" :color="$hero->faction->color">
        {{ strtoupper(substr($hero->name, 0, 1)) }}
      </x-badge>
    @endif
  </x-slot:badge>

  <div class="card-summary">
    <x-info-item icon="heroes" label="{{ $hero->race->name . ' ' . $hero->heroClass->name }}" />
  </div>
  
  <x-slot:details>
    @if($hero->lore_text)
      <x-description>
        <p>{{ $hero->lore_text }}</p>
      </x-description>
    @endif
  </x-slot:details>
</x-cards.entity-card>