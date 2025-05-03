<?php
namespace App\Services\Admin;

use App\Models\ContentPage;
use App\Services\ImageService;
use App\Services\Admin\ContentSectionService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ContentPageService
{
  public function __construct(
    protected ImageService $imageService,
    protected ContentSectionService $sectionService
  ) {}

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

  public function delete(ContentPage $page): bool
  {
    foreach ($page->sections as $section) {
      $this->sectionService->delete($section);
    }
    
    $this->deleteBackgroundImage($page);
    return $page->delete();
  }

  protected function handleImageUpload(UploadedFile $file, ContentPage $page): string
  {
    return $this->imageService->store($file, $page->getImageDirectory());
  }

  protected function deleteBackgroundImage(ContentPage $page): void
  {
    if ($page->background_image) {
      $this->imageService->delete($page->background_image);
    }
  }
}