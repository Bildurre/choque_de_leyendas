<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait HasFilters
{
  /**
   * Apply all admin filters to the query builder
   *
   * @param Builder $query
   * @param Request $request
   * @return Builder
   */
  public function scopeApplyAdminFilters(Builder $query, Request $request): Builder
  {
    // Apply search filters
    $this->applySearchFilters($query, $request);

    // Apply column filters
    $this->applyColumnFilters($query, $request);
    
    // Apply sort filters
    $this->applySortFilters($query, $request);
    
    // In the future, we'll add column filters here
    // $this->applyColumnFilters($query, $request);
    
    return $query;
  }
  
  /**
   * Apply search filters to the query
   *
   * @param Builder $query
   * @param Request $request
   * @return void
   */
  protected function applySearchFilters(Builder $query, Request $request): void
  {
    // Apply search filter if search term is provided
    if ($request->has('search') && !empty($request->search)) {
      $searchTerm = '%' . trim($request->search) . '%';
      $thisTable = $this->getTable();
      
      // Always include 'name' field and any additional searchable fields if method exists
      $searchable = ['name'];
      
      if (method_exists($this, 'getAdminSearchable')) {
        $additionalFields = $this->getAdminSearchable();
        // Remove 'name' if it's already in the additional fields to avoid duplicates
        $additionalFields = array_filter($additionalFields, function($field) {
          return $field !== 'name';
        });
        
        // Merge the fields
        $searchable = array_merge($searchable, $additionalFields);
      }
      
      $query->where(function (Builder $query) use ($searchTerm, $searchable, $thisTable) {
        foreach ($searchable as $field) {
          // Check if it's a relation.field
          if (Str::contains($field, '.')) {
            $parts = explode('.', $field);
            $relation = $parts[0];
            $relationField = $parts[1];
            
            $query->orWhereHas($relation, function ($q) use ($relationField, $searchTerm) {
              // Use lower() for case-insensitive search
              $q->whereRaw('LOWER(' . $relationField . ') LIKE ?', [strtolower($searchTerm)]);
            });
          } else {
            // Regular field with case-insensitive search - now with explicit table name
            $query->orWhereRaw('LOWER(' . $thisTable . '.' . $field . ') LIKE ?', [strtolower($searchTerm)]);
          }
        }
      });
    }
  }
  
  /**
   * Apply sort filters to the query
   *
   * @param Builder $query
   * @param Request $request
   * @return void
   */
  protected function applySortFilters(Builder $query, Request $request): void
  {
    // Apply sorting if sort field is provided and model has sortable fields
    if (method_exists($this, 'getAdminSortable') && $request->has('sort')) {
      $sortable = $this->getAdminSortable();
      $sortField = $request->sort;
      $sortDirection = $request->has('direction') && $request->direction === 'desc' ? 'desc' : 'asc';
      
      // Find the configuration for the requested sort field
      $sortConfig = null;
      foreach ($sortable as $config) {
        if (is_array($config) && isset($config['field']) && $config['field'] === $sortField) {
          $sortConfig = $config;
          break;
        } elseif (is_string($config) && $config === $sortField) {
          $sortConfig = ['field' => $config];
          break;
        }
      }
      
      if ($sortConfig) {
        // Check if this is a custom sort field
        if (isset($sortConfig['custom_sort'])) {
          $this->applyCustomSort($query, $sortConfig['custom_sort'], $sortDirection);
        } else {
          $this->applySortToField($query, $sortConfig['field'], $sortDirection);
        }
      }
    }
  }
  
  /**
   * Find the field to sort by in the sortable configuration
   *
   * @param array $sortable
   * @param string $sortField
   * @return string|null
   */
  protected function getFieldToSort(array $sortable, string $sortField): ?string
  {
      foreach ($sortable as $key => $config) {
          if (is_string($config) && $config === $sortField) {
              return $config;
          } elseif (is_array($config) && isset($config['field']) && $config['field'] === $sortField) {
              return $config['field'];
          }
      }
      
      return null;
  }
  
  /**
   * Apply sorting to a specific field
   *
   * @param Builder $query
   * @param string $field
   * @param string $direction
   * @return void
   */
  protected function applySortToField(Builder $query, string $field, string $direction): void
  {
    // Check if it's a relation.field
    if (Str::contains($field, '.')) {
      $this->applySortToRelation($query, $field, $direction);
      return;
    }
    
    // Check if this is a translatable field
    if ($this->isTranslatableField($field)) {
      $this->applySortToTranslatableField($query, $field, $direction);
      return;
    }
    
    // Regular field sorting
    $query->orderBy($field, $direction);
  }

  /**
   * Check if a field is translatable
   *
   * @param string $field
   * @return bool
   */
  protected function isTranslatableField(string $field): bool
  {
    return property_exists($this, 'translatable') && in_array($field, $this->translatable);
  }

  /**
   * Apply sorting to a translatable field based on current locale
   *
   * @param Builder $query
   * @param string $field
   * @param string $direction
   * @return void
   */
  protected function applySortToTranslatableField(Builder $query, string $field, string $direction): void
  {
    // Get current locale
    $locale = app()->getLocale();
    
    // Order by the specific locale value using JSON extraction
    // The exact SQL syntax might vary depending on the database driver
    $driver = config('database.default');
    
    if ($driver === 'mysql' || $driver === 'mariadb') {
      // MySQL/MariaDB syntax
      $query->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT({$field}, '$.{$locale}')) {$direction}");
    } elseif ($driver === 'pgsql') {
      // PostgreSQL syntax
      $query->orderByRaw("{$field}->'{$locale}' {$direction}");
    } elseif ($driver === 'sqlite') {
      // SQLite syntax (may require additional functions)
      $query->orderByRaw("json_extract({$field}, '$.{$locale}') {$direction}");
    } else {
      // Fallback to standard (non-locale specific) ordering
      $query->orderBy($field, $direction);
    }
  }
  
  /**
   * Apply sorting to a relation field
   *
   * @param Builder $query
   * @param string $field
   * @param string $direction
   * @return void
   */
  protected function applySortToRelation(Builder $query, string $field, string $direction): void
  {
    $parts = explode('.', $field);
    $relation = $parts[0];
    $relationField = $parts[1];
    
    // Get relation details
    $relationModel = $this->{$relation}()->getRelated();
    $relationTable = $relationModel->getTable();
    $thisTable = $this->getTable();
    $foreignKey = $this->{$relation}()->getForeignKeyName();
    $ownerKey = $this->{$relation}()->getOwnerKeyName();
    
    // Check if the query already has the join
    $joins = collect($query->getQuery()->joins);
    $hasJoin = $joins->where('table', $relationTable)->count() > 0;
    
    // Add the join only if not already added
    if (!$hasJoin) {
      $query->join(
        $relationTable, 
        "{$thisTable}.{$foreignKey}", 
        '=', 
        "{$relationTable}.{$ownerKey}"
      );
    }
    
    // Add select to avoid column collision
    if (!collect($query->getQuery()->columns)->contains("{$thisTable}.*")) {
      $query->select("{$thisTable}.*");
    }
    
    // Check if this is a translatable field in the related model
    if (property_exists($relationModel, 'translatable') && in_array($relationField, $relationModel->translatable)) {
      // Get current locale
      $locale = app()->getLocale();
      
      // Order by the specific locale value using JSON extraction
      $driver = config('database.default');
      
      if ($driver === 'mysql' || $driver === 'mariadb') {
        // MySQL/MariaDB syntax
        $query->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT({$relationTable}.{$relationField}, '$.{$locale}')) {$direction}");
      } elseif ($driver === 'pgsql') {
        // PostgreSQL syntax
        $query->orderByRaw("{$relationTable}.{$relationField}->'{$locale}' {$direction}");
      } elseif ($driver === 'sqlite') {
        // SQLite syntax
        $query->orderByRaw("json_extract({$relationTable}.{$relationField}, '$.{$locale}') {$direction}");
      } else {
        // Fallback to standard ordering
        $query->orderBy("{$relationTable}.{$relationField}", $direction);
      }
    } else {
      // Regular field
      $query->orderBy("{$relationTable}.{$relationField}", $direction);
    }
  }

  /**
   * Apply custom sorting to the query
   *
   * @param Builder $query
   * @param string $sortType
   * @param string $direction
   * @return void
   */
  protected function applyCustomSort(Builder $query, string $sortType, string $direction): void
  {
    switch ($sortType) {
      case 'cost_total':
        // Sort by total cost (length of cost string)
        $query->orderByRaw("LENGTH(cost) {$direction}");
        break;
        
      case 'cost_order':
        // Utilizamos una aproximación con múltiples campos de ordenación
        
        // Para ordenar por la cantidad de dados de cada color
        // R primero, luego G, luego B cuando es ascendente
        // B primero, luego G, luego R cuando es descendente
        if ($direction === 'asc') {
          $query->orderByRaw("
            (LENGTH(cost) - LENGTH(REPLACE(UPPER(cost), 'R', ''))) DESC,
            (LENGTH(cost) - LENGTH(REPLACE(UPPER(cost), 'G', ''))) DESC,
            (LENGTH(cost) - LENGTH(REPLACE(UPPER(cost), 'B', ''))) DESC
          ");
        } else {
          $query->orderByRaw("
            (LENGTH(cost) - LENGTH(REPLACE(UPPER(cost), 'B', ''))) DESC,
            (LENGTH(cost) - LENGTH(REPLACE(UPPER(cost), 'G', ''))) DESC,
            (LENGTH(cost) - LENGTH(REPLACE(UPPER(cost), 'R', ''))) DESC
          ");
        }
        break;
    }
  }

  /**
   * Apply column filters to the query
   *
   * @param Builder $query
   * @param Request $request
   * @return void
   */
  protected function applyColumnFilters(Builder $query, Request $request): void
  {
    // Check if model has filterable fields
    if (!method_exists($this, 'getAdminFilterable')) {
      return;
    }
    
    $filterables = $this->getAdminFilterable();
    $thisTable = $this->getTable();
    
    foreach ($filterables as $filter) {
      // Skip if no field defined
      if (!isset($filter['field'])) {
        continue;
      }
      
      $fieldName = $filter['field'];
      $paramName = str_replace('.', '_', $fieldName);
      
      // Check if filter is applied
      if (!$request->has($paramName) || empty($request->input($paramName))) {
        continue;
      }
      
      // Get filter values
      $filterValues = $request->input($paramName);
      if (!is_array($filterValues)) {
        $filterValues = [$filterValues];
      }
      
      // Skip if no values selected
      if (empty($filterValues)) {
        continue;
      }
      
      // Apply filter based on type
      switch ($filter['type']) {
        case 'enum':
          $query->whereIn($thisTable . '.' . $fieldName, $filterValues);
          break;
          
        case 'relation':
          $query->whereIn($thisTable . '.' . $filter['field'], $filterValues);
          break;
          
        case 'nested_relation':
          $fieldParts = explode('.', $fieldName);
          if (count($fieldParts) >= 2) {
            $relation = $fieldParts[0];
            $relationField = $fieldParts[1];
            
            $query->whereHas($relation, function ($q) use ($relationField, $filterValues) {
              $q->whereIn($relationField, $filterValues);
            });
          }
          break;
          
        case 'cost_range':
          $this->applyCostRangeFilter($query, $filterValues);
          break;
          
        case 'cost_colors':
          $this->applyCostColorsFilter($query, $filterValues);
          break;

        case 'cost_exact':
          $this->applyCostExactFilter($query, $filterValues);
          break;
      }
    }
  }
  
  /**
   * Apply filter on relation field
   *
   * @param Builder $query
   * @param string $field
   * @param array $values
   * @return void
   */
  protected function applyRelationFilter(Builder $query, string $field, array $values): void
  {
    $parts = explode('.', $field);
    
    // Check if it's a nested relation
    if (count($parts) > 2) {
      // For nested relations like heroClass.heroSuperclass.id
      $relation = $parts[0];
      $nestedField = implode('.', array_slice($parts, 1));
      
      $query->whereHas($relation, function ($q) use ($nestedField, $values) {
        $this->applyRelationFilter($q, $nestedField, $values);
      });
    } else {
      // Simple relation like heroClass.id
      $relation = $parts[0];
      $relationField = $parts[1];
      
      $query->whereHas($relation, function ($q) use ($relationField, $values) {
        $q->whereIn($relationField, $values);
      });
    }
  }

  /**
   * Apply cost range filter to the query
   * 
   * @param Builder $query
   * @param array $values
   * @return void
   */
  protected function applyCostRangeFilter(Builder $query, array $values): void
  {
    $query->where(function ($q) use ($values) {
      foreach ($values as $value) {
        if ($value === '5') {
          // For "5+" range
          $q->orWhereRaw('LENGTH(cost) >= 5');
        } else {
          // For specific cost number
          $q->orWhereRaw('LENGTH(cost) = ?', [$value]);
        }
      }
    });
  }

  /**
   * Apply cost colors filter to the query
   * 
   * @param Builder $query
   * @param array $values
   * @return void
   */
  protected function applyCostColorsFilter(Builder $query, array $values): void
  {
    $query->where(function ($q) use ($values) {
      foreach ($values as $color) {
        $q->orWhere('cost', 'LIKE', '%' . $color . '%');
      }
    });
  }

  /**
   * Apply cost exact filter to the query
   * Filters for cards with the exact cost specified
   * 
   * @param Builder $query
   * @param array $values
   * @return void
   */
  protected function applyCostExactFilter(Builder $query, array $values): void
  {
    // Primero vamos a normalizar los valores de coste (ordenarlos según R, G, B)
    $normalizedValues = array_map(function($cost) {
      // Función para ordenar un coste (R antes que G antes que B)
      $costArray = str_split(strtoupper($cost));
      $red = '';
      $green = '';
      $blue = '';
      
      foreach ($costArray as $dice) {
        if ($dice === 'R') $red .= 'R';
        elseif ($dice === 'G') $green .= 'G';
        elseif ($dice === 'B') $blue .= 'B';
      }
      
      return $red . $green . $blue;
    }, $values);
    
    // Ahora aplicamos el filtro usando una función SQL para ordenar el coste
    $query->where(function($q) use ($normalizedValues) {
      foreach ($normalizedValues as $normalizedCost) {
        // Para cada coste normalizado, comparamos con el coste de la carta normalizado
        $q->orWhereRaw("
          CONCAT(
            REPEAT('R', LENGTH(cost) - LENGTH(REPLACE(UPPER(cost), 'R', ''))),
            REPEAT('G', LENGTH(cost) - LENGTH(REPLACE(UPPER(cost), 'G', ''))),
            REPEAT('B', LENGTH(cost) - LENGTH(REPLACE(UPPER(cost), 'B', '')))
          ) = ?
        ", [$normalizedCost]);
      }
    });
  }
}