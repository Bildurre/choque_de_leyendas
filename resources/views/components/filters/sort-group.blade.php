@props([
  'model',
  'request',
  'context' => 'admin'
])

@php
  $isPublic = $context === 'public';
  $sortableMethod = $isPublic ? 'getPublicSortable' : 'getAdminSortable';
  
  // Obtener los campos ordenables del modelo
  $sortableFields = method_exists($model, $sortableMethod) ? $model->{$sortableMethod}() : [];
  
  // Obtener el campo actual de ordenación y la dirección
  $currentSort = $request->sort ?? '';
  $currentDirection = $request->direction ?? 'asc';
  
  $translationPrefix = $isPublic ? 'public.filters' : 'admin.filters';
@endphp

@if(count($sortableFields) > 0)
  <div class="filters-sort">
    <span class="filters-sort__label">{{ __($translationPrefix . '.sort_by') }}:</span>
    
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
        
        <a href="{{ $sortUrl }}" class="{{ $buttonClass }}" title="{{ __($translationPrefix . '.sort_by_field', ['field' => $fieldLabel]) }}">
          <span class="filters-sort__button-text">{{ $fieldLabel }}</span>
          
          @if($isActive)
            @if($currentDirection === 'asc')
              <x-icon name="arrow-up" class="filters-sort__button-icon" size="sm" />
            @else
              <x-icon name="arrow-down" class="filters-sort__button-icon" size="sm" />
            @endif
          @else
            <x-icon name="arrow-down-up" class="filters-sort__button-icon filters-sort__button-icon--neutral" size="sm" />
          @endif
        </a>
      @endforeach
    </div>
  </div>
@endif