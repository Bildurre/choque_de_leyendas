@props([
  'heroSuperclass',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-game.cards.entity-card
  :borderColor="$heroSuperclass->color"
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="hero-superclass-name"
  :deleteConfirmValue="$heroSuperclass->name"
  containerClass="hero-superclass-card"
  :title="$heroSuperclass->name"
>
  
  <x-slot:badge>
    @if($heroSuperclass->icon)
      <x-core.badge variant="icon">
        <img src="{{ asset('storage/' . $heroSuperclass->icon) }}" alt="{{ $heroSuperclass->name }}">
      </x-core.badge>
    @else
      <x-core.badge variant="icon">
        {{ strtoupper(substr($heroSuperclass->name, 0, 1)) }}
      </x-core.badge>
    @endif
  </x-slot:badge>

  <div class="card-summary">
    <x-core.stat-item icon="heroes" :count="$heroSuperclass->hero_classes_count" label="clase" />
  </div>
</x-game.cards.entity-card>