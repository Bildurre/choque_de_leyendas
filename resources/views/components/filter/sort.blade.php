@props([
  'model',
  'request'
])

@php
  // Obtener los campos ordenables del modelo
  $sortableFields = method_exists($model, 'getAdminSortable') ? $model->getAdminSortable() : [];
  
  // Obtener el campo actual de ordenación y la dirección
  $currentSort = $request->sort ?? '';
  $currentDirection = $request->direction ?? 'asc';
@endphp

@if(count($sortableFields) > 0)
  <div class="filters-sort">
    <span class="filters-sort__label">{{ __('admin.filters.sort_by') }}:</span>
    
    <div class="filters-sort__buttons">
      @foreach($sortableFields as $field)
        @php
          // Extraer datos del campo
          $fieldName = is_array($field) ? $field['field'] : $field;
          $fieldLabel = is_array($field) ? ($field['label'] ?? $fieldName) : $fieldName;
          
          // Determinar si este campo está activo
          $isActive = $currentSort === $fieldName;
          
          // Determinar la próxima dirección para este campo
          $nextDirection = $isActive && $currentDirection === 'asc' ? 'desc' : 'asc';
          
          // Construir la URL para ordenar por este campo
          $sortUrl = url()->current() . '?' . http_build_query(
            array_merge(
              $request->all(),
              ['sort' => $fieldName, 'direction' => $nextDirection]
            )
          );
          
          // Clase para el botón
          $buttonClass = 'filters-sort__button';
          $buttonClass .= $isActive ? ' filters-sort__button--active' : '';
        @endphp
        
        <a href="{{ $sortUrl }}" class="{{ $buttonClass }}" title="{{ __('admin.filters.sort_by_field', ['field' => $fieldLabel]) }}">
          <span class="filters-sort__button-text">{{ $fieldLabel }}</span>
          
          @if($isActive)
            @if($currentDirection === 'asc')
              <x-icon name="chevron-up" class="filters-sort__button-icon" size="sm" />
            @else
              <x-icon name="chevron-down" class="filters-sort__button-icon" size="sm" />
            @endif
          @else
            <x-icon name="arrow-down-up" class="filters-sort__button-icon filters-sort__button-icon--neutral" size="sm" />
          @endif
        </a>
      @endforeach
    </div>
  </div>
@endif