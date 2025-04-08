@props([
  'ability',
  'showRoute' => null,
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.admin.entity-card
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
      <x-common.badge type="passive">Pasiva</x-common.badge>
    @else
      <x-widgets.cost-display :cost="$ability->cost" />
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
      <x-common.stat-item icon="heroes" :count="$ability->heroes_count ?? 0" label="héroe" />
    </div>
  </div>
  
  <x-slot:details>
    <x-common.description-section title="Descripción">
      <div class="description-content">
        {!! $ability->description !!}
      </div>
    </x-common.description-section>
  </x-slot:details>
</x-cards.admin.entity-card>