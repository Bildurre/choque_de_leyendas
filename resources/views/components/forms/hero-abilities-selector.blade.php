@props([
  'selectedAbilities' => [], 
  'abilities' => []
])

<div class="hero-abilities-selector">
  <h3 class="abilities-section-title">Asignar Habilidades</h3>
  
  <div class="abilities-filter">
    <div class="input-group">
      <input type="text" id="abilities-search" class="form-input" placeholder="Buscar habilidades...">
      
      @php
        // Extraer los tipos, subtipos y rangos únicos
        $types = [];
        $subtypes = [];
        $ranges = [];
        
        foreach($abilities as $ability) {
          // Extraer tipos
          if ($ability->subtype && $ability->subtype->type) {
            $typeName = $ability->subtype->type->name;
            if (!in_array($typeName, $types)) {
              $types[] = $typeName;
            }
          }
          
          // Extraer subtipos
          if ($ability->subtype) {
            $subtypeName = $ability->subtype->name;
            if (!in_array($subtypeName, $subtypes)) {
              $subtypes[] = $subtypeName;
            }
          }
          
          // Extraer rangos
          if ($ability->range) {
            $rangeName = $ability->range->name;
            if (!in_array($rangeName, $ranges)) {
              $ranges[] = $rangeName;
            }
          }
        }
        
        sort($types);
        sort($subtypes);
        sort($ranges);
      @endphp
      
      <div class="filter-selects">
        <select id="abilities-type-filter" class="form-select">
          <option value="">Todos los tipos</option>
          @foreach($types as $typeName)
            <option value="{{ $typeName }}">{{ $typeName }}</option>
          @endforeach
        </select>
        
        <select id="abilities-subtype-filter" class="form-select">
          <option value="">Todos los subtipos</option>
          @foreach($subtypes as $subtypeName)
            <option value="{{ $subtypeName }}">{{ $subtypeName }}</option>
          @endforeach
        </select>
        
        <select id="abilities-range-filter" class="form-select">
          <option value="">Todos los rangos</option>
          @foreach($ranges as $rangeName)
            <option value="{{ $rangeName }}">{{ $rangeName }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
  
  <div class="abilities-container">
    <div class="available-abilities">
      <h4>Habilidades Disponibles</h4>
      <div class="abilities-list" id="available-abilities-list">
        @foreach($abilities as $ability)
          @if(!in_array($ability->id, $selectedAbilities))
            <div class="ability-card" 
                 data-id="{{ $ability->id }}"
                 data-type="{{ $ability->subtype->type->name ?? '' }}"
                 data-subtype="{{ $ability->subtype->name ?? '' }}"
                 data-range="{{ $ability->range->name ?? '' }}"
                 data-name="{{ $ability->name }}">
              <div class="ability-header">
                <div class="ability-cost">
                  <x-cost-display :cost="$ability->cost"/>
                </div>
                <h5 class="ability-name">{{ $ability->name }}</h5>
              </div>
              <div class="ability-types">
                <span class="ability-type">{{ $ability->subtype->type->name ?? 'Sin tipo' }}</span>
                <span class="ability-subtype">{{ $ability->subtype->name ?? 'Sin subtipo' }}</span>
                <span class="ability-range">{{ $ability->range->name ?? 'Sin rango' }}</span>
              </div>
              <div class="ability-description">
                {!! $ability->description !!}
              </div>
              <button type="button" class="add-ability-btn" data-id="{{ $ability->id }}">
                <x-icon name="plus" />
              </button>
            </div>
          @endif
        @endforeach
      </div>
    </div>
    
    <div class="selected-abilities">
      <h4>Habilidades Seleccionadas</h4>
      <div class="abilities-list" id="selected-abilities-list">
        @foreach($abilities as $ability)
          @if(in_array($ability->id, $selectedAbilities))
            <div class="ability-card" 
                 data-id="{{ $ability->id }}"
                 data-type="{{ $ability->subtype->type->name ?? '' }}"
                 data-subtype="{{ $ability->subtype->name ?? '' }}"
                 data-range="{{ $ability->range->name ?? '' }}"
                 data-name="{{ $ability->name }}">
              <div class="ability-header">
                <div class="ability-cost">
                  <x-cost-display :cost="$ability->cost"/>
                </div>
                <h5 class="ability-name">{{ $ability->name }}</h5>
              </div>
              <div class="ability-types">
                <span class="ability-type">{{ $ability->subtype->type->name ?? 'Sin tipo' }}</span>
                <span class="ability-subtype">{{ $ability->subtype->name ?? 'Sin subtipo' }}</span>
                <span class="ability-range">{{ $ability->range->name ?? 'Sin rango' }}</span>
              </div>
              <div class="ability-description">
                {!! $ability->description !!}
              </div>
              <button type="button" class="remove-ability-btn" data-id="{{ $ability->id }}">
                <x-icon name="delete" />
              </button>
              
              <input type="hidden" name="abilities[]" value="{{ $ability->id }}">
            </div>
          @endif
        @endforeach
      </div>
    </div>
  </div>
</div>