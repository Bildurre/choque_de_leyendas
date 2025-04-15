@props([
  'heroSuperclass',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.entity-card
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
      <div class="icon-badge">
        <img src="{{ asset('storage/' . $heroSuperclass->icon) }}" alt="{{ $heroSuperclass->name }}">
      </div>
    @else
      <div class="icon-badge"">
        {{ strtoupper(substr($heroSuperclass->name, 0, 1)) }}
      </div>
    @endif
  </x-slot:badge>

  <div class="card-summary">
    <div class="stat-item-grid">
      <x-stat-item icon="heroes" :count="$heroSuperclass->hero_classes_count" label="clase" />
    </div>
  </div>
</x-cards.entity-card>