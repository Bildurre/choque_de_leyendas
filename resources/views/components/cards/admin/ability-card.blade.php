@props([
  'ability',
  'editRoute' => null,
  'deleteRoute' => null
])

<x-cards.admin.entity-card
  :borderColor="$ability->subtype && $ability->subtype->color ? $ability->subtype->color : '#666666'"
  :editRoute="$editRoute"
  :deleteRoute="$deleteRoute"
  deleteConfirmAttribute="ability-name"
  :deleteConfirmValue="$ability->name"
  containerClass="ability-card"
  :title="$ability->name"
  :hasDetails="true"
>
  <div class="ability-summary">
    <div class="stat-item-grid">
      <x-common.stat-item icon="heroes" :count="$ability->heroes_count ?? 0" label="héroe" />
    </div>
  </div>
  
  <x-slot:details>
    <div class="ability-details-content">
      <div class="ability-attributes">
        <div class="ability-cost">
          <span class="attribute-label">Coste:</span>
          <x-widgets.cost-display :cost="$ability->cost" :showTotal="false" />
        </div>
        
        @if($ability->range)
          <div class="ability-range">
            <span class="attribute-label">Rango:</span>
            <span class="attribute-value">
              @if($ability->range->icon)
                <img src="{{ asset('storage/' . $ability->range->icon) }}" alt="{{ $ability->range->name }}" class="range-icon">
              @endif
              {{ $ability->range->name }}
            </span>
          </div>
        @endif
        
        @if($ability->subtype)
          <div class="ability-type">
            <span class="attribute-label">Tipo:</span>
            <span class="attribute-value" 
                  style="color: {{ $ability->subtype->type->color }}">
              {{ $ability->subtype->type->name }}
            </span>
          </div>
          
          <div class="ability-subtype">
            <span class="attribute-label">Subtipo:</span>
            <span class="attribute-value"
                  style="color: {{ $ability->subtype->color }}">
              {{ $ability->subtype->name }}
            </span>
          </div>
        @endif
      </div>
      
      @if($ability->description)
      <x-common.description-section title="Descripción">
        <{!! $ability->description !!}</>
      </x-common.description-section>
      @endif
      
      @if($ability->heroes && $ability->heroes->count() > 0)
        <div class="ability-heroes-section">
          <h4>Héroes con esta habilidad</h4>
          <div class="ability-heroes-grid">
            @foreach($ability->heroes as $hero)
              <div class="ability-hero-item">
                <span class="ability-hero-name">{{ $hero->name }}</span>
                @if($hero->heroClass)
                  <span class="ability-hero-class">{{ $hero->heroClass->name }}</span>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </x-slot:details>
</x-cards.admin.entity-card>