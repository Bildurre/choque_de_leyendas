<x-entity-card
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="attack-type-name"
  :deleteConfirmValue="$type->name"
  containerClass="attack-type-card"
  :title="$type->name"
  :hasDetails="$type->description || $type->subtypes_count > 0"
>
  <div class="type-summary">
    <div class="type-stats">
      <div class="stat-item">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M20 20v-8l-8 8M8 16H4v-4"></path>
          <path d="M4 4h16v4M12 12v-4"></path>
        </svg>
        <span>{{ $type->subtypes_count ?? 0 }} {{ Str::plural('subtipo', $type->subtypes_count ?? 0) }}</span>
      </div>
    </div>
  </div>
  
  <x-slot:details>
    @if($type->description)
      <div class="type-description">
        <h4>Descripción</h4>
        <p>{{ $type->description }}</p>
      </div>
    @endif
    
    @if($type->subtypes_count > 0 && isset($type->subtypes))
      <div class="type-subtypes">
        <h4>Subtipos</h4>
        <div class="subtypes-list">
          @foreach($type->subtypes as $subtype)
            <div class="subtype-item">
              <span class="subtype-name">{{ $subtype->name }}</span>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </x-slot:details>
</x-entity-card>