<?php

namespace App\Models\Traits;

/**
 * Trait for models that can be published or remain in draft state
 */
trait HasPublishedAttribute
{
  /**
   * Check if the model is published
   * 
   * @return bool
   */
  public function isPublished(): bool
  {
    return (bool) $this->is_published;
  }

  /**
   * Toggle published state
   * 
   * @return bool
   */
  public function togglePublished(): bool
  {
    $this->is_published = !$this->is_published;
    return $this->save();
  }

  /**
   * Scope a query to only include published models
   * 
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopePublished($query)
  {
    // Always specify the table to avoid ambiguity when joining
    return $query->where($this->getTable() . '.is_published', true);
  }

  /**
   * Scope a query to only include draft models
   * 
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeDraft($query)
  {
    // Always specify the table to avoid ambiguity when joining
    return $query->where($this->getTable() . '.is_published', false);
  }
}