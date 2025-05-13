@props([
  'name' => 'entities',
  'label' => null,
  'entities' => [],
  'selected' => [],
  'required' => false,
  'maxCopies' => 1,
  'showCopies' => false,
  'entityType' => 'entity', // Puede ser 'card', 'hero', 'ability'
  'mainField' => 'name',
  'secondaryField' => 'type',
  'secondaryFieldClass' => '',
  'detailsView' => null, // Vista parcial para renderizar detalles específicos
])

@php
  // Traducir los textos según el tipo de entidad
  $entityTypePlural = __("form.entity_selector.{$entityType}s");
  $searchPlaceholder = __("form.entity_selector.search_{$entityType}s");
  $availableLabel = __("form.entity_selector.available_{$entityType}s");
  $selectedLabel = __("form.entity_selector.selected_{$entityType}s");
  $noEntitiesSelected = __("form.entity_selector.no_{$entityType}s_selected");
  $noEntitiesAvailable = __("form.entity_selector.no_{$entityType}s_available");
@endphp

<div class="form-field">
  @if($label)
    <x-form.label :for="$name" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  <div class="entity-selector" data-entity-type="{{ $entityType }}">
    <div class="entity-selector__controls">
      <div class="entity-selector__search">
        <input type="text" class="form-input entity-selector__search-input" placeholder="{{ $searchPlaceholder }}" id="{{ $entityType }}-search-input">
        <button type="button" class="entity-selector__search-clear" id="{{ $entityType }}-search-clear">
          <x-icon name="x" size="sm" />
        </button>
      </div>
    </div>

    <div class="entity-selector__container">
      <div class="entity-selector__available">
        <h3 class="entity-selector__title">{{ $availableLabel }}</h3>
        <div class="entity-selector__list">
          @forelse($entities as $entity)
            @php
              $isSelected = false;
              $copies = 0;
              
              // Determinar si la entidad está seleccionada
              if (is_array($selected)) {
                foreach($selected as $selectedEntity) {
                  if(isset($selectedEntity['id']) && $selectedEntity['id'] == $entity->id) {
                    $isSelected = true;
                    $copies = $selectedEntity['copies'] ?? 1;
                    break;
                  }
                }
              }
              
              // Obtener el valor del campo secundario y preparar la clase CSS
              $secondaryValue = '';
              $secondaryClass = '';
              
              if (is_string($secondaryField) && !empty($secondaryField)) {
                // Intentar acceder al campo directamente
                if (isset($entity->{$secondaryField})) {
                  $secondaryValue = $entity->{$secondaryField};
                }
                // Intentar acceder a través de una relación
                elseif (str_contains($secondaryField, '.')) {
                  $parts = explode('.', $secondaryField);
                  $relationObj = $entity;
                  
                  foreach ($parts as $part) {
                    if (isset($relationObj->{$part})) {
                      $relationObj = $relationObj->{$part};
                    } else {
                      $relationObj = null;
                      break;
                    }
                  }
                  
                  if ($relationObj !== null) {
                    $secondaryValue = $relationObj;
                  }
                }
                
                // Preparar la clase CSS si $secondaryFieldClass es una función o un string
                if (is_callable($secondaryFieldClass)) {
                  $secondaryClass = $secondaryFieldClass($entity, $secondaryValue);
                } elseif (is_string($secondaryFieldClass) && !empty($secondaryFieldClass)) {
                  $secondaryClass = $secondaryFieldClass;
                  
                  // Si se proporciona un campo para derivar una clase dinámica
                  if (isset($entity->{$secondaryFieldClass})) {
                    $secondaryClass = $entity->{$secondaryFieldClass};
                  }
                }
              }
              
              // Obtener el valor del campo principal
              $mainValue = $entity->{$mainField} ?? '';
            @endphp
            
            <div class="entity-selector__item {{ $isSelected ? 'is-selected' : '' }}" 
                 data-entity-id="{{ $entity->id }}" 
                 data-entity-name="{{ $mainValue }}" 
                 data-entity-type="{{ $secondaryValue }}">
              <input type="checkbox" id="{{ $entityType }}_{{ $entity->id }}" name="{{ $name }}[]" value="{{ $entity->id }}" class="entity-selector__checkbox" {{ $isSelected ? 'checked' : '' }}>
              
              <div class="entity-selector__content">
                <div class="entity-selector__header">
                  <div class="entity-selector__name">{{ $mainValue }}</div>
                  
                  @if($secondaryValue)
                    <div class="entity-selector__type {{ $secondaryClass }}">
                      {{ $secondaryValue }}
                    </div>
                  @endif
                </div>
                
                @if($detailsView && view()->exists($detailsView))
                  <div class="entity-selector__details">
                    @include($detailsView, ['entity' => $entity])
                  </div>
                @endif
                
                @if($showCopies)
                  <div class="entity-selector__copies">
                    <label class="entity-selector__copies-label">{{ __('form.entity_selector.copies') }}:</label>
                    <div class="entity-selector__copies-controls">
                      <button type="button" class="entity-selector__copies-btn entity-selector__copies-btn--decrease" {{ $copies <= 1 ? 'disabled' : '' }} data-max-copies="{{ $maxCopies }}">-</button>
                      <input 
                        type="number" 
                        name="{{ $name }}[{{ $entity->id }}][copies]" 
                        class="entity-selector__copies-input" 
                        value="{{ $copies ?: 1 }}" 
                        min="1" 
                        max="{{ $maxCopies }}" 
                        {{ !$isSelected ? 'disabled' : '' }}
                      >
                      <button type="button" class="entity-selector__copies-btn entity-selector__copies-btn--increase" {{ $copies >= $maxCopies ? 'disabled' : '' }} data-max-copies="{{ $maxCopies }}">+</button>
                      <input type="hidden" name="{{ $name }}[{{ $entity->id }}][id]" value="{{ $entity->id }}" {{ !$isSelected ? 'disabled' : '' }}>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          @empty
            <div class="entity-selector__empty">
              <p>{{ $noEntitiesAvailable }}</p>
            </div>
          @endforelse
        </div>
      </div>
      
      <div class="entity-selector__selected">
        <h3 class="entity-selector__title">{{ $selectedLabel }}</h3>
        <div class="entity-selector__selected-list" id="{{ $entityType }}s-selected-list">
          <div class="entity-selector__placeholder">{{ $noEntitiesSelected }}</div>
        </div>
      </div>
    </div>
  </div>
  
  <x-form.error :name="$name" />
</div>