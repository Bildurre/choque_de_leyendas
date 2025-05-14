@props([
  'model',
  'request'
])

@php
  $filterables = method_exists($model, 'getAdminFilterable') ? $model->getAdminFilterable() : [];
@endphp

@if(count($filterables) > 0)
  <div class="filters-select-group">
    @foreach($filterables as $filter)
      @php
        $options = [];
        $fieldName = $filter['field'];
        $paramName = str_replace('.', '_', $fieldName);
        $label = $filter['label'] ?? $fieldName;
        
        // Valores seleccionados actualmente
        $selectedValues = $request->input($paramName, []);
        if (!is_array($selectedValues)) {
          $selectedValues = [$selectedValues];
        }
        
        // Obtener opciones según el tipo de filtro
        switch ($filter['type']) {
          case 'enum':
            $options = $filter['options'] ?? [];
            break;
            
          case 'relation':
            try {
              $relation = $filter['relation'];
              if (method_exists($model, $relation)) {
                $relatedModel = $model->{$relation}()->getRelated();
                $labelField = $filter['option_label'] ?? 'name';
                $valueField = $filter['option_value'] ?? 'id';
                
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
              if (isset($filter['option_model'])) {
                // Usar el modelo directamente si está especificado
                $modelClass = $filter['option_model'];
                $labelField = $filter['option_label'] ?? 'name';
                $valueField = $filter['option_value'] ?? 'id';
                
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
      
      <x-filter.filter-select
        :name="$paramName"
        :label="$label"
        :options="$options"
        :selected="$selectedValues"
        :multiple="true"
      />
    @endforeach
  </div>
@endif