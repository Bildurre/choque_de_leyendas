@props([
  'faction',
  'showRoute' => null,
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.admin.entity-card
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
      <div class="faction-icon">
        <img src="{{ asset('storage/' . $faction->icon) }}" alt="{{ $faction->name }}">
      </div>
    @else
      <div class="icon-badge" style="background-color: {{ $faction->color }}">
        {{ strtoupper(substr($faction->name, 0, 1)) }}
      </div>
    @endif
  </x-slot:badge>
  
  <div class="faction-summary">
    <div class="faction-stats">
      <x-common.stat-item icon="heroes" :count="$faction->heroes_count ?? 0" label="héroe" />
      <x-common.stat-item icon="cards" :count="$faction->cards_count ?? 0" label="carta" />
    </div>
  </div>
  
  <x-slot:details>
    @if($faction->lore_text)
      <x-common.description-section title="Descripción">
        <p>{{ $faction->lore_text }}</p>
      </x-common.description-section>
    @endif
  </x-slot:details>
</x-cards.admin.entity-card>