@props([
  'model',
  'request',
  'context' => 'admin'
])

@php
  $isPublic = $context === 'public';
  $filterableMethod = $isPublic ? 'getPublicFilterable' : 'getAdminFilterable';
  $filterables = method_exists($model, $filterableMethod) ? $model->{$filterableMethod}() : [];
@endphp

@if(count($filterables) > 0)
  <div class="filters-select-group">
    @foreach($filterables as $filters)
      @php
        $options = [];
        $fieldName = $filters['field'];
        $paramName = str_replace('.', '_', $fieldName);
        $label = $filters['label'] ?? $fieldName;
        
        // Valores seleccionados actualmente
        $selectedValues = $request->input($paramName, []);
        if (!is_array($selectedValues)) {
          $selectedValues = [$selectedValues];
        }
        
        // Obtener opciones según el tipo de filtro
        switch ($filters['type']) {
          case 'enum':
          case 'cost_range':
          case 'cost_colors':
          case 'cost_exact':
          case 'attribute_range':
            $options = $filters['options'] ?? [];
            break;
            
          case 'relation':
            try {
              $relation = $filters['relation'];
              if (method_exists($model, $relation)) {
                $relatedModel = $model->{$relation}()->getRelated();
                $labelField = $filters['option_label'] ?? 'name';
                $valueField = $filters['option_value'] ?? 'id';
                
                // Solo mostrar items publicados en filtros públicos
                $query = $relatedModel::query();
                if ($isPublic && method_exists($relatedModel, 'scopePublished')) {
                  $query->published();
                }
                
                if (property_exists($relatedModel, 'translatable') && in_array($labelField, $relatedModel->translatable)) {
                  // Para campos traducibles
                  $items = $query->get();
                  foreach ($items as $item) {
                    $options[$item->{$valueField}] = $item->{$labelField};
                  }
                } else {
                  // Para campos normales
                  $options = $query->pluck($labelField, $valueField)->toArray();
                }
              }
            } catch (\Exception $e) {
              // Manejar error silenciosamente
              $options = [];
            }
            break;
            
          case 'nested_relation':
            try {
              if (isset($filters['option_model'])) {
                // Usar el modelo directamente si está especificado
                $modelClass = $filters['option_model'];
                $labelField = $filters['option_label'] ?? 'name';
                $valueField = $filters['option_value'] ?? 'id';
                
                if (class_exists($modelClass)) {
                  $instance = new $modelClass;
                  
                  // Solo mostrar items publicados en filtros públicos
                  $query = $modelClass::query();
                  if ($isPublic && method_exists($instance, 'scopePublished')) {
                    $query->published();
                  }
                  
                  if (property_exists($instance, 'translatable') && in_array($labelField, $instance->translatable)) {
                    // Para campos traducibles
                    $items = $query->get();
                    foreach ($items as $item) {
                      $options[$item->{$valueField}] = $item->{$labelField};
                    }
                  } else {
                    // Para campos normales
                    $options = $query->pluck($labelField, $valueField)->toArray();
                  }
                }
              }
            } catch (\Exception $e) {
              // Manejar error silenciosamente
              $options = [];
            }
            break;
        }
      @endphp
      
      @if(count($options) > 0)
        @if(in_array($filters['type'], ['cost_exact', 'cost_colors']))
          <x-filters.cost-filter-select
            :name="$paramName"
            :label="$label"
            :options="$options"
            :selected="$selectedValues"
            :multiple="true"
            :type="$filters['type']"
          />
        @else
          <x-filters.filters-select
            :name="$paramName"
            :label="$label"
            :options="$options"
            :selected="$selectedValues"
            :multiple="true"
          />
        @endif
      @endif
    @endforeach
  </div>
@endif