<?php

namespace App\Services\Pdf;

use App\Models\Card;
use App\Models\Hero;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class TemporaryCollectionService
{
  private const SESSION_KEY = 'pdf_temporary_collection';
  
  /**
   * Add an entity to the temporary collection
   */
  public function addEntity(string $type, int $entityId, int $copies = 1): bool
  {
    $collection = $this->getCollection();
    $key = $this->generateKey($type, $entityId);
    
    // Check if entity exists
    if (!$this->entityExists($type, $entityId)) {
      return false;
    }
    
    // Add or update in collection
    $collection[$key] = [
      'type' => $type,
      'entity_id' => $entityId,
      'copies' => $copies,
      'added_at' => now()->toISOString(),
    ];
    
    $this->saveCollection($collection);
    return true;
  }
  
  /**
   * Remove an entity from the temporary collection
   */
  public function removeEntity(string $type, int $entityId): bool
  {
    $collection = $this->getCollection();
    $key = $this->generateKey($type, $entityId);
    
    if (isset($collection[$key])) {
      unset($collection[$key]);
      $this->saveCollection($collection);
      return true;
    }
    
    return false;
  }
  
  /**
   * Update the number of copies for an entity
   */
  public function updateCopies(string $type, int $entityId, int $copies): bool
  {
    $collection = $this->getCollection();
    $key = $this->generateKey($type, $entityId);
    
    if (isset($collection[$key]) && $copies > 0) {
      $collection[$key]['copies'] = $copies;
      $this->saveCollection($collection);
      return true;
    }
    
    return false;
  }
  
  /**
   * Clear the entire collection
   */
  public function clearCollection(): void
  {
    Session::forget(self::SESSION_KEY);
  }
  
  /**
   * Get all items in the collection
   */
  public function getItems(): Collection
  {
    $collection = $this->getCollection();
    $items = collect();
    
    foreach ($collection as $item) {
      $entity = $this->loadEntity($item['type'], $item['entity_id']);
      
      if ($entity) {
        $items->push([
          'type' => $item['type'],
          'entity' => $entity,
          'copies' => $item['copies'],
          'added_at' => $item['added_at'],
        ]);
      }
    }
    
    return $items->sortBy('added_at');
  }
  
  /**
   * Get items count
   */
  public function getItemsCount(): int
  {
    return count($this->getCollection());
  }
  
  /**
   * Get total cards count (including copies)
   */
  public function getTotalCardsCount(): int
  {
    $collection = $this->getCollection();
    $total = 0;
    
    foreach ($collection as $item) {
      $total += $item['copies'];
    }
    
    return $total;
  }
  
  /**
   * Check if an entity is in the collection
   */
  public function hasEntity(string $type, int $entityId): bool
  {
    $collection = $this->getCollection();
    $key = $this->generateKey($type, $entityId);
    
    return isset($collection[$key]);
  }
  
  /**
   * Get the number of copies for an entity
   */
  public function getCopies(string $type, int $entityId): int
  {
    $collection = $this->getCollection();
    $key = $this->generateKey($type, $entityId);
    
    return isset($collection[$key]) ? $collection[$key]['copies'] : 0;
  }
  
  /**
   * Check if collection has items
   */
  public function hasItems(): bool
  {
    return !empty($this->getCollection());
  }
  
  /**
   * Get the raw collection from session
   */
  private function getCollection(): array
  {
    return Session::get(self::SESSION_KEY, []);
  }
  
  /**
   * Save the collection to session
   */
  private function saveCollection(array $collection): void
  {
    Session::put(self::SESSION_KEY, $collection);
  }
  
  /**
   * Generate a unique key for an entity
   */
  private function generateKey(string $type, int $entityId): string
  {
    return "{$type}_{$entityId}";
  }
  
  /**
   * Check if an entity exists
   */
  private function entityExists(string $type, int $entityId): bool
  {
    return match($type) {
      'card' => Card::where('id', $entityId)->published()->exists(),
      'hero' => Hero::where('id', $entityId)->published()->exists(),
      default => false,
    };
  }
  
  /**
   * Load an entity by type and ID
   */
  private function loadEntity(string $type, int $entityId): ?object
  {
    return match($type) {
      'card' => Card::with(['faction'])->published()->find($entityId),
      'hero' => Hero::with(['faction'])->published()->find($entityId),
      default => null,
    };
  }
}