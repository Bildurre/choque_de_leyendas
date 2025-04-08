@props([
  'ability',
  'showRoute' => null,
  'editRoute' => null,
  'deleteRoute' => null
])

<x-entity-card
  :borderColor="$ability->subtype && $ability->subtype->color ? $ability->subtype->color : '#666666'"
  :showRoute="$showRoute"
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="ability-name"
  :deleteConfirmValue="$ability->name"
  containerClass="ability-card"
  :title="$ability->name"
  :hasDetails="true"
>
  <x-slot:badge>
    @if($ability->is_passive)
      <span class="ability-badge passive-badge">Pasiva</span>
    @else
      <x-game.cost-display :cost="$ability->cost" />
    @endif
  </x-slot:badge>

  <div class="ability-summary">
    <div class="ability-meta">
      @if($ability->subtype)
        <span class="attack-type" 
              style="background-color: {{ $ability->subtype->type->color }}; color: {{ $ability->subtype->type->text_is_dark ? '#000000' : '#ffffff' }}">
          {{ $ability->subtype->type->name }}
        </span>
        <span class="attack-subtype">{{ $ability->subtype->name }}</span>
      @endif
      
      @if($ability->range)
        <span class="attack-range">
          @if($ability->range->icon)
            <img src="{{ asset('storage/' . $ability->range->icon) }}" alt="{{ $ability->range->name }}" class="range-icon">
          @endif
          {{ $ability->range->name }}
        </span>
      @endif
    </div>
    
    <div class="ability-stats">
      <span class="stat-item">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
        <span>{{ $ability->heroes_count ?? 0 }} {{ Str::plural('héroe', $ability->heroes_count ?? 0) }}</span>
      </span>
    </div>
  </div>
  
  <x-slot:details>
    <div class="ability-description">
      <h4>Descripción</h4>
      <div class="description-content">
        {!! $ability->description !!}
      </div>
    </div>
  </x-slot:details>
</x-entity-card>