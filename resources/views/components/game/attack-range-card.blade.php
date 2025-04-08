@props([
  'range',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="attack-range-name"
  :deleteConfirmValue="$range->name"
  containerClass="attack-range-card"
  :title="$range->name"
  :hasDetails="$range->description ? true : false"
>
  <div class="range-summary">
    <div class="range-stats">
      <div class="stat-item">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
        </svg>
        <span>{{ $range->abilities_count ?? 0 }} {{ Str::plural('habilidad', $range->abilities_count ?? 0) }}</span>
      </div>
    </div>
    
    @if($range->icon)
      <div class="range-icon-preview">
        <img src="{{ asset('storage/' . $range->icon) }}" alt="{{ $range->name }}">
      </div>
    @endif
  </div>
  
  @if($range->description)
    <x-slot:details>
      <div class="range-description">
        <h4>Descripción</h4>
        <p>{{ $range->description }}</p>
      </div>
    </x-slot:details>
  @endif
</x-entity-card>