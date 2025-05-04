<?php

namespace App\Services\Content;

use App\Models\ContentPage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use App\Services\Media\ImageService;
use Illuminate\Database\Eloquent\Collection;

class ContentPageService
{
  /**
   * The image service instance.
   *
   * @var ImageService
   */
  protected $imageService;

  /**
   * The content section service instance.
   *
   * @var ContentSectionService
   */
  protected $sectionService;

  /**
   * Create a new service instance.
   *
   * @param ImageService $imageService
   * @param ContentSectionService $sectionService
   */
  public function __construct(
    ImageService $imageService,
    ContentSectionService $sectionService
  ) {
    $this->imageService = $imageService;
    $this->sectionService = $sectionService;
  }

  /**
   * Create a new content page.
   *
   * @param array $data
   * @return ContentPage
   */
  public function create(array $data): ContentPage
  {
    if (empty($data['slug'])) {
      $data['slug'] = Str::slug($data['title']);
    }
    
    $page = new ContentPage($data);
    
    if (isset($data['background_image']) && $data['background_image'] instanceof UploadedFile) {
      $page->background_image = $this->handleImageUpload($data['background_image'], $page);
    }
    
    $page->save();
    return $page;
  }

  /**
   * Update an existing content page.
   *
   * @param ContentPage $page
   * @param array $data
   * @return ContentPage
   */
  public function update(ContentPage $page, array $data): ContentPage
  {
    $page->fill($data);
    
    if (isset($data['background_image'])) {
      if ($data['background_image'] instanceof UploadedFile) {
        $this->deleteBackgroundImage($page);
        $page->background_image = $this->handleImageUpload($data['background_image'], $page);
      } elseif ($data['background_image'] === '' && $page->background_image) {
        $this->deleteBackgroundImage($page);
        $page->background_image = null;
      }
    }
    
    $page->save();
    return $page;
  }

  /**
   * Delete a content page.
   *
   * @param ContentPage $page
   * @return bool
   */
  public function delete(ContentPage $page): bool
  {
    foreach ($page->sections as $section) {
      $this->sectionService->delete($section);
    }
    
    $this->deleteBackgroundImage($page);
    return $page->delete();
  }

  /**
   * Handle uploading image for a content page.
   *
   * @param UploadedFile $file
   * @param ContentPage $page
   * @return string
   */
  protected function handleImageUpload(UploadedFile $file, ContentPage $page): string
  {
    return $this->imageService->store($file, $page->getImageDirectory());
  }

  /**
   * Delete background image of a content page.
   *
   * @param ContentPage $page
   * @return void
   */
  protected function deleteBackgroundImage(ContentPage $page): void
  {
    if ($page->background_image) {
      $this->imageService->delete($page->background_image);
    }
  }

  /**
   * Get a published page by its slug.
   *
   * @param string $slug
   * @return ContentPage|null
   */
  public function getPublishedBySlug(string $slug): ?ContentPage
  {
    return $this->getPageByTypeOrSlug('slug', $slug);
  }

  /**
   * Get the published home page.
   *
   * @return ContentPage|null
   */
  public function getPublishedHome(): ?ContentPage
  {
    return $this->getPageByTypeOrSlug('type', 'home');
  }

  /**
   * Get the published rules page.
   *
   * @return ContentPage|null
   */
  public function getPublishedRulesPage(): ?ContentPage
  {
    return $this->getPageByTypeOrSlug('type', 'rules');
  }

  /**
   * Get the published components page.
   *
   * @return ContentPage|null
   */
  public function getPublishedComponentsPage(): ?ContentPage
  {
    return $this->getPageByTypeOrSlug('type', 'components');
  }

  /**
   * Get the published annexes page.
   *
   * @return ContentPage|null
   */
  public function getPublishedAnnexesPage(): ?ContentPage
  {
    return $this->getPageByTypeOrSlug('type', 'annexes');
  }

  /**
   * Get a page by its type or slug with all relationships.
   *
   * @param string $field
   * @param string $value
   * @return ContentPage|null
   */
  protected function getPageByTypeOrSlug(string $field, string $value): ?ContentPage
  {
    return ContentPage::where($field, $value)
      ->where('is_published', true)
      ->with(['sections' => function($query) {
        $query->orderBy('order')->with(['blocks' => function($blockQuery) {
          $blockQuery->orderBy('order');
        }]);
      }])
      ->first();
  }

  /**
   * Get all published pages.
   *
   * @return Collection
   */
  public function getAllPublishedPages(): Collection
  {
    return ContentPage::where('is_published', true)
      ->select(['id', 'title', 'slug', 'type', 'meta_description'])
      ->orderBy('order')
      ->get();
  }

  /**
   * Get pages by type.
   *
   * @param string $type
   * @param bool $isPublished
   * @return Collection
   */
  public function getPagesByType(string $type, bool $isPublished = true): Collection
  {
    $query = ContentPage::where('type', $type);
    
    if ($isPublished) {
      $query->where('is_published', true);
    }
    
    return $query->get();
  }

  /**
   * Search in content pages.
   *
   * @param string $term
   * @param bool $onlyPublished
   * @return Collection
   */
  public function searchPages(string $term, bool $onlyPublished = true): Collection
  {
    $query = ContentPage::query();
    
    if ($onlyPublished) {
      $query->where('is_published', true);
    }
    
    // Search in all translatable fields
    $query->where(function($q) use ($term) {
      $q->whereJsonContains('title', $term)
        ->orWhereJsonContains('meta_description', $term);
    });
    
    return $query->get();
  }
}