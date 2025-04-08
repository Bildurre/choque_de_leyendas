<x-cards.admin.entity-card
  :borderColor="$subtype->color"
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
      <span class="subtype-type-badge">
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
  </div>
  
  @if($subtype->description)
    <x-slot:details>
      <div class="subtype-description">
        <h4>Descripción</h4>
        <p>{{ $subtype->description }}</p>
      </div>
    </x-slot:details>
  @endif
</x-cards.admin.entity-card>