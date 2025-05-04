<?php

namespace App\Services\Content;

use App\Models\ContentPage;
use App\Models\ContentSection;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Database\Eloquent\Collection;

class ContentSectionService
{
  use HandlesTranslations;

  protected $translatableFields = ['title'];

  /**
   * Get all sections for a page
   */
  public function getSections(ContentPage $page): Collection
  {
    return $page->sections;
  }

  /**
   * Create a new section
   */
  public function create(ContentPage $page, array $data): ContentSection
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $section = new ContentSection();
    $section->content_page_id = $page->id;
    
    // Apply translations
    $this->applyTranslations($section, $data, $this->translatableFields);
    
    // Set other fields
    $section->anchor_id = $data['anchor_id'] ?? null;
    $section->order = $data['order'] ?? 0;
    $section->include_in_index = $data['include_in_index'] ?? true;
    $section->background_color = $data['background_color'] ?? null;
    
    $section->save();
    
    return $section;
  }

  /**
   * Update an existing section
   */
  public function update(ContentSection $section, array $data): ContentSection
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($section, $data, $this->translatableFields);
    
    // Update other fields
    if (isset($data['anchor_id'])) {
      $section->anchor_id = $data['anchor_id'];
    }
    
    if (isset($data['order'])) {
      $section->order = $data['order'];
    }
    
    if (isset($data['include_in_index'])) {
      $section->include_in_index = $data['include_in_index'];
    }
    
    if (isset($data['background_color'])) {
      $section->background_color = $data['background_color'];
    }
    
    $section->save();
    
    return $section;
  }

  /**
   * Delete a section
   */
  public function delete(ContentSection $section): bool
  {
    return $section->delete();
  }

  /**
   * Reorder sections
   */
  public function reorder(ContentPage $page, array $sectionIds): bool
  {
    $order = 0;
    
    foreach ($sectionIds as $sectionId) {
      $section = ContentSection::find($sectionId);
      
      if ($section && $section->content_page_id === $page->id) {
        $section->order = $order++;
        $section->save();
      }
    }
    
    return true;
  }
}