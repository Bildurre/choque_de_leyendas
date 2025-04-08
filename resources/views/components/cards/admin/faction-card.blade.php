@props([
  'faction',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.admin.entity-card
  :borderColor="$faction->color"
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
      <div class="faction-badge" style="background-color: {{ $faction->color }}">
        {{ strtoupper(substr($faction->name, 0, 1)) }}
      </div>
    @endif
  </x-slot:badge>
  
  <div class="faction-summary">
    <div class="faction-stats">
      <div class="stat-item">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
        <span>{{ $faction->heroes_count ?? 0 }} {{ Str::plural('héroe', $faction->heroes_count ?? 0) }}</span>
      </div>
      <div class="stat-item">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
        <span>{{ $faction->cards_count ?? 0 }} {{ Str::plural('carta', $faction->cards_count ?? 0) }}</span>
      </div>
    </div>
  </div>
  
  <x-slot:details>
    @if($faction->lore_text)
      <div class="faction-lore">
        <h4>Descripción</h4>
        <p>{{ $faction->lore_text }}</p>
      </div>
    @endif
  </x-slot:details>
</x-cards.admin.entity-card>