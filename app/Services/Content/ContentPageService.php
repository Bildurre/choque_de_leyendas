<?php

namespace App\Services\Content;

use App\Models\ContentPage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use App\Services\Media\ImageService;
use App\Services\Traits\HandlesTranslations;

class ContentPageService
{
  use HandlesTranslations;

  protected $imageService;
  protected $translatableFields = ['title', 'meta_description'];
  
  /**
   * Create a new service instance.
   *
   * @param ImageService $imageService
   */
  public function __construct(ImageService $imageService)
  {
    $this->imageService = $imageService;
  }

  /**
   * Get all published pages
   */
  public function getPublishedPages(): Collection
  {
    return ContentPage::where('is_published', true)
      ->where('parent_slug', null)
      ->orderBy('order')
      ->get();
  }

  /**
   * Get all pages (for admin)
   */
  public function getAllPages(): Collection
  {
    return ContentPage::orderBy('parent_slug')
      ->orderBy('order')
      ->get();
  }

  /**
   * Get a page by its slug
   */
  public function getPageBySlug(string $slug): ?ContentPage
  {
    return ContentPage::where('slug', $slug)->first();
  }

  /**
   * Create a new page
   */
  public function create(array $data): ContentPage
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $page = new ContentPage();
    
    // Apply translations
    $this->applyTranslations($page, $data, $this->translatableFields);
    
    // Set non-translatable fields
    $page->slug = isset($data['slug']) && !empty($data['slug']) 
      ? $data['slug'] 
      : str_slug($page->getTranslation('title', app()->getLocale()));
    
    $page->is_published = $data['is_published'] ?? false;
    $page->order = $data['order'] ?? 0;
    $page->show_in_menu = $data['show_in_menu'] ?? true;
    $page->parent_slug = $data['parent_slug'] ?? null;
    
    // Handle image if provided
    if (isset($data['background_image']) && $data['background_image'] instanceof UploadedFile) {
      $page->background_image = $this->imageService->store(
        $data['background_image'], 
        'images/uploads/content-pages'
      );
    }
    
    $page->save();
    
    return $page;
  }

  /**
   * Update an existing page
   */
  public function update(ContentPage $page, array $data): ContentPage
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($page, $data, $this->translatableFields);
    
    // Update slug if necessary
    if (isset($data['slug']) && !empty($data['slug'])) {
      $page->slug = $data['slug'];
    }
    
    // Update other non-translatable fields
    if (isset($data['is_published'])) {
      $page->is_published = $data['is_published'];
    }
    
    if (isset($data['order'])) {
      $page->order = $data['order'];
    }
    
    if (isset($data['show_in_menu'])) {
      $page->show_in_menu = $data['show_in_menu'];
    }
    
    if (isset($data['parent_slug'])) {
      $page->parent_slug = $data['parent_slug'];
    }
    
    // Handle image removal
    if (isset($data['remove_background_image']) && $data['remove_background_image'] == "1") {
      $this->imageService->delete($page->background_image);
      $page->background_image = null;
    }
    // Handle image update
    elseif (isset($data['background_image']) && $data['background_image'] instanceof UploadedFile) {
      $page->background_image = $this->imageService->update(
        $data['background_image'], 
        $page->background_image, 
        'images/uploads/content-pages'
      );
    }
    
    $page->save();
    
    return $page;
  }

  /**
   * Delete a page
   */
  public function delete(ContentPage $page): bool
  {
    // Delete background image if exists
    if ($page->background_image) {
      $this->imageService->delete($page->background_image);
    }
    
    // Delete the page and its sections and blocks
    return $page->delete();
  }
}