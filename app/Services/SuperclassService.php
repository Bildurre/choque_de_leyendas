<?php

namespace App\Services;

use App\Models\Superclass;
use Illuminate\Database\Eloquent\Collection;

class SuperclassService
{
  /**
   * Get all superclasses with count of related hero classes
   *
   * @return Collection
   */
  public function getAllSuperclasses(): Collection
  {
    return Superclass::withCount('heroClasses')->get();
  }

  /**
   * Create a new superclass
   *
   * @param array $data
   * @return Superclass
   */
  public function create(array $data): Superclass
  {
    $superclass = new Superclass();
    $superclass->fill($data);
    $superclass->save();
    
    return $superclass;
  }

  /**
   * Update an existing superclass
   *
   * @param Superclass $superclass
   * @param array $data
   * @return Superclass
   */
  public function update(Superclass $superclass, array $data): Superclass
  {
    $superclass->fill($data);
    $superclass->save();
    
    return $superclass;
  }

  /**
   * Delete a superclass
   *
   * @param Superclass $superclass
   * @return bool
   * @throws \Exception
   */
  public function delete(Superclass $superclass): bool
  {
    // Check for related hero classes
    if ($superclass->heroClasses()->count() > 0) {
      throw new \Exception("No se puede eliminar la superclase porque está siendo utilizada por clases de héroe.");
    }
    
    return $superclass->delete();
  }
}