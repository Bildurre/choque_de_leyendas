@props([
  'model',
  'request'
])

@php
  $filterables = method_exists($model, 'getAdminFilterable') ? $model->getAdminFilterable() : [];
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
            $options = $filters['options'] ?? [];
            break;
            
          case 'relation':
            try {
              $relation = $filters['relation'];
              if (method_exists($model, $relation)) {
                $relatedModel = $model->{$relation}()->getRelated();
                $labelField = $filters['option_label'] ?? 'name';
                $valueField = $filters['option_value'] ?? 'id';
                
                if (property_exists($relatedModel, 'translatable') && in_array($labelField, $relatedModel->translatable)) {
                  // Para campos traducibles
                  $items = $relatedModel::all();
                  foreach ($items as $item) {
                    $options[$item->{$valueField}] = $item->{$labelField};
                  }
                } else {
                  // Para campos normales
                  $options = $relatedModel::all()->pluck($labelField, $valueField)->toArray();
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
                  
                  if (property_exists($instance, 'translatable') && in_array($labelField, $instance->translatable)) {
                    // Para campos traducibles
                    $items = $modelClass::all();
                    foreach ($items as $item) {
                      $options[$item->{$valueField}] = $item->{$labelField};
                    }
                  } else {
                    // Para campos normales
                    $options = $modelClass::all()->pluck($labelField, $valueField)->toArray();
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
      
      <x-filters.filters-select
        :name="$paramName"
        :label="$label"
        :options="$options"
        :selected="$selectedValues"
        :multiple="true"
      />
    @endforeach
  </div>
@endif