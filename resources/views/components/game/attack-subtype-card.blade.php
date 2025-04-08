@props([
  'subtype',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-entity-card
  :borderColor="$subtype->color ?: ($subtype->type ? $subtype->type->color : '#666666')"
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="attack-subtype-name"
  :deleteConfirmValue="$subtype->name"
  containerClass="attack-subtype-card"
  :title="$subtype->name"
  :hasDetails="$subtype->description ? true : false"
>
  <x-slot:badge>
    @if($subtype->type)
      <span class="subtype-type-badge" 
            style="background-color: {{ $subtype->type->color }}; color: {{ $subtype->type->text_is_dark ? '#000000' : '#ffffff' }}">
        {{ $subtype->type->name }}
      </span>
    @endif
  </x-slot:badge>

  <div class="subtype-summary">
    <div class="subtype-stats">
      <div class="stat-item">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
        </svg>
        <span>{{ $subtype->abilities_count ?? 0 }} {{ Str::plural('habilidad', $subtype->abilities_count ?? 0) }}</span>
      </div>
    </div>
    
    @if($subtype->color)
      <div class="color-sample" style="background-color: {{ $subtype->color }}"></div>
    @endif
  </div>
  
  @if($subtype->description)
    <x-slot:details>
      <div class="subtype-description">
        <h4>Descripción</h4>
        <p>{{ $subtype->description }}</p>
      </div>
    </x-slot:details>
  @endif
</x-entity-card>