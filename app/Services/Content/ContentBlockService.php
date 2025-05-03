<?php
namespace App\Services\Content;

use App\Models\ContentBlock;
use App\Services\ImageService;
use Illuminate\Http\UploadedFile;

class ContentBlockService extends ImageService
{
  public function __construct(
    protected ImageService $imageService
  ) {}

  public function create(array $data): ContentBlock
  {
    $block = new ContentBlock($data);
    
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $block->image = $this->handleImageUpload($data['image'], $block);
    }
    
    $block->save();
    return $block;
  }

  public function update(ContentBlock $block, array $data): ContentBlock
  {
    $block->fill($data);
    
    if (isset($data['image'])) {
      if ($data['image'] instanceof UploadedFile) {
        $this->deleteImage($block);
        $block->image = $this->handleImageUpload($data['image'], $block);
      } elseif ($data['image'] === '' && $block->image) {
        $this->deleteImage($block);
        $block->image = null;
      }
    }
    
    $block->save();
    return $block;
  }

  public function delete(ContentBlock $block): bool
  {
    $this->deleteImage($block);
    return $block->delete();
  }

  protected function handleImageUpload(UploadedFile $file, ContentBlock $block): string
  {
    return $this->imageService->store($file, $block->getImageDirectory());
  }

  protected function deleteImage(ContentBlock $block): void
  {
    if ($block->image) {
      $this->imageService->delete($block->image);
    }
  }

  public function reorder(ContentBlock $block, int $newOrder): void
  {
    $section = $block->section;
    $blocks = $section->blocks()->where('id', '!=', $block->id)
      ->orderBy('order')
      ->get();
    
    $blocks->splice($newOrder, 0, [$block]);
    
    foreach ($blocks as $index => $item) {
      $item->order = $index;
      $item->save();
    }
  }
}