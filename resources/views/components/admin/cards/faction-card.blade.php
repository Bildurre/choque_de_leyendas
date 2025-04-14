@props([
  'faction',
  'showRoute' => null,
  'editRoute' => null,
  'deleteRoute' => null
])

<x-admin.cards.entity-card
  :borderColor="$faction->color"
  :showRoute="$showRoute"
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="faction-name"
  :deleteConfirmValue="$faction->name"
  containerClass="faction-card"
  :title="$faction->name"
  :hasDetails="true"
>
  <x-slot:badge>
    @if($faction->icon)
      <x-badge variant="icon">
        <img src="{{ asset('storage/' . $faction->icon) }}" alt="{{ $faction->name }}">
      </x-badge>
    @else
      <x-badge variant="icon" color="{{ $faction->color }}">
        {{ strtoupper(substr($faction->name, 0, 1)) }}
      </x-badge>
    @endif
  </x-slot:badge>
  
  <div class="card-summary">
    <x-stat-item icon="heroes" :count="$faction->heroes_count ?? 0" label="héroe" />
    <x-stat-item icon="cards" :count="$faction->cards_count ?? 0" label="carta" />
  </div>
  
  <x-slot:details>
    @if($faction->lore_text)
      <x-description>
        <p>{{ $faction->lore_text }}</p>
      </x-description>
    @endif
  </x-slot:details>
</x-admin.cards.entity-card>