<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait HasAdminFilters
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
    // Apply search filter if search term is provided and model has searchable fields
    if ($request->has('search') && !empty($request->search) && method_exists($this, 'getAdminSearchable')) {
      $searchTerm = '%' . trim($request->search) . '%';
      $searchable = $this->getAdminSearchable();
      $thisTable = $this->getTable();
      
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
          
          // Get actual field from either simple string or configuration
          $fieldToSort = $this->getFieldToSort($sortable, $sortField);
          
          if ($fieldToSort) {
              $this->applySortToField($query, $fieldToSort, $sortDirection);
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
}