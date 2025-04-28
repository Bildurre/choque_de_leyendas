<?php

namespace App\Services;

use App\Models\Faction;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Collection;

class FactionService
{
  protected $imageService;

  /**
   * Create a new FactionService instance
   *
   * @param ImageService $imageService
   */
  public function __construct(ImageService $imageService)
  {
    $this->imageService = $imageService;
  }

  /**
   * Get all factions
   *
   * @return Collection
   */
  public function getAllFactions(): Collection
  {
    return Faction::all();
  }

  /**
   * Create a new faction
   *
   * @param array $data
   * @return Faction
   */
  public function create(array $data): Faction
  {
    $faction = new Faction();
    
    // Procesar campos traducibles separados por idioma
    $currentLocale = app()->getLocale();
    $availableLocales = config('app.available_locales', ['es']);
    
    // Nombre (obligatorio al menos en el idioma actual)
    if (isset($data['name'][$currentLocale])) {
      foreach ($availableLocales as $locale) {
        if (isset($data['name'][$locale]) && !empty($data['name'][$locale])) {
          $faction->setTranslation('name', $locale, $data['name'][$locale]);
        } elseif ($locale === $currentLocale) {
          // Si falta el idioma actual, usar el valor proporcionado directamente
          $faction->setTranslation('name', $locale, $data['name'] ?? '');
          break;
        }
      }
    } else {
      // Compatibilidad con versión anterior - si es un string simple
      $faction->setTranslation('name', $currentLocale, $data['name'] ?? '');
    }
    
    // Texto de lore (opcional)
    if (isset($data['lore_text'])) {
      if (is_array($data['lore_text'])) {
        foreach ($availableLocales as $locale) {
          if (isset($data['lore_text'][$locale])) {
            $faction->setTranslation('lore_text', $locale, $data['lore_text'][$locale]);
          }
        }
      } else {
        // Compatibilidad con versión anterior - si es un string simple
        $faction->setTranslation('lore_text', $currentLocale, $data['lore_text']);
      }
    }
    
    $faction->color = $data['color'];
    
    // Set text color based on background
    $faction->setTextColorBasedOnBackground();
    
    // Handle icon if provided
    if (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $faction->icon = $this->imageService->store($data['icon'], $faction->getImageDirectory());
    }
    
    $faction->save();
    
    return $faction;
  }

  /**
   * Update an existing faction
   *
   * @param Faction $faction
   * @param array $data
   * @return Faction
   */
  public function update(Faction $faction, array $data): Faction
  {
    // Procesar campos traducibles separados por idioma
    $availableLocales = config('app.available_locales', ['es']);
    
    // Nombre
    if (isset($data['name']) && is_array($data['name'])) {
      foreach ($availableLocales as $locale) {
        if (isset($data['name'][$locale]) && !empty($data['name'][$locale])) {
          $faction->setTranslation('name', $locale, $data['name'][$locale]);
        }
      }
    } elseif (isset($data['name'])) {
      // Compatibilidad con versión anterior - si es un string simple
      $faction->setTranslation('name', app()->getLocale(), $data['name']);
    }
    
    // Texto de lore
    if (isset($data['lore_text']) && is_array($data['lore_text'])) {
      foreach ($availableLocales as $locale) {
        if (isset($data['lore_text'][$locale])) {
          $faction->setTranslation('lore_text', $locale, $data['lore_text'][$locale]);
        }
      }
    } elseif (isset($data['lore_text'])) {
      // Compatibilidad con versión anterior - si es un string simple
      $faction->setTranslation('lore_text', app()->getLocale(), $data['lore_text']);
    }
    
    // Otros campos no traducibles
    $faction->color = $data['color'];
    
    // Set text color based on background
    $faction->setTextColorBasedOnBackground();
    
    // Handle icon removal
    if (isset($data['remove_icon']) && $data['remove_icon'] == "1") {
      $this->imageService->delete($faction->icon);
      $faction->icon = null;
    }
    // Handle icon update
    elseif (isset($data['icon']) && $data['icon'] instanceof UploadedFile) {
      $faction->icon = $this->imageService->update($data['icon'], $faction->icon, $faction->getImageDirectory());
    }
    
    $faction->save();
    
    return $faction;
  }
  
  /**
   * Delete a faction
   *
   * @param Faction $faction
   * @return bool
   * @throws \Exception
   */
  public function delete(Faction $faction): bool
  {
    // Check for related entities
    if ($faction->heroes()->count() > 0 || $faction->cards()->count() > 0) {
      throw new \Exception("No se puede eliminar la facción porque tiene héroes o cartas asociadas.");
    }
    
    // Delete icon if exists
    if ($faction->icon) {
      $this->imageService->delete($faction->icon);
    }
    
    return $faction->delete();
  }
}