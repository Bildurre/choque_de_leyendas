<?php

namespace App\Services\Content;

use App\Models\ContentBlock;
use App\Models\ContentPage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use App\Services\Traits\HandlesTranslations;

class ContentBlockService
{
  use HandlesTranslations;

  protected $contentImageService;
  protected $translatableFields = ['content'];

  /**
   * Create a new service instance.
   *
   * @param ContentImageService $contentImageService
   */
  public function __construct(ContentImageService $contentImageService)
  {
    $this->contentImageService = $contentImageService;
  }

  /**
   * Get all blocks for a page
   */
  public function getBlocksByPage(ContentPage $page): Collection
  {
    return $page->blocks;
  }

  /**
   * Create a new block
   */
  public function create(ContentPage $page, array $data): ContentBlock
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $block = new ContentBlock();
    $block->content_page_id = $page->id;
    $block->type = $data['type'];
    
    // Apply translations
    $this->applyTranslations($block, $data, $this->translatableFields);
    
    // Set other fields
    $block->order = $data['order'] ?? 0;
    $block->anchor_id = $data['anchor_id'] ?? null;
    $block->background_color = $data['background_color'] ?? null;
    $block->include_in_index = $data['include_in_index'] ?? false;
    $block->image_position = $data['image_position'] ?? 'none';
    
    // For model_list blocks
    if ($block->type === 'model_list') {
      $block->model_type = $data['model_type'] ?? null;
      $block->model_filters = $data['model_filters'] ?? null;
    }
    
    // Handle style settings
    $block->style_settings = $data['style_settings'] ?? null;
    
    // Handle image if provided
    if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $block->image = $this->contentImageService->storeBlockImage($data['image']);
    }
    
    $block->save();
    
    return $block;
  }

  /**
   * Update an existing block
   */
  public function update(ContentBlock $block, array $data): ContentBlock
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($block, $data, $this->translatableFields);
    
    // Update other fields
    if (isset($data['type'])) {
      $block->type = $data['type'];
    }
    
    if (isset($data['order'])) {
      $block->order = $data['order'];
    }
    
    if (isset($data['anchor_id'])) {
      $block->anchor_id = $data['anchor_id'];
    }
    
    if (isset($data['background_color'])) {
      $block->background_color = $data['background_color'];
    }
    
    if (isset($data['include_in_index'])) {
      $block->include_in_index = $data['include_in_index'];
    }
    
    if (isset($data['image_position'])) {
      $block->image_position = $data['image_position'];
    }
    
    // For model_list blocks
    if ($block->type === 'model_list') {
      if (isset($data['model_type'])) {
        $block->model_type = $data['model_type'];
      }
      
      if (isset($data['model_filters'])) {
        $block->model_filters = $data['model_filters'];
      }
    }
    
    // Handle style settings
    if (isset($data['style_settings'])) {
      $block->style_settings = $data['style_settings'];
    }
    
    // Handle image removal
    if (isset($data['remove_image']) && $data['remove_image'] == "1") {
      $this->contentImageService->deleteBlockImage($block->image);
      $block->image = null;
    }
    // Handle image update
    elseif (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $block->image = $this->contentImageService->updateBlockImage(
        $data['image'], 
        $block->image
      );
    }
    
    $block->save();
    
    return $block;
  }

  /**
   * Delete a block
   */
  public function delete(ContentBlock $block): bool
  {
    // Delete image if exists
    if ($block->image) {
      $this->contentImageService->deleteBlockImage($block->image);
    }
    
    return $block->delete();
  }

  /**
   * Reorder blocks
   */
  public function reorder(ContentPage $page, array $blockIds): bool
  {
    $order = 0;
    
    foreach ($blockIds as $blockId) {
      $block = ContentBlock::find($blockId);
      
      if ($block && $block->content_page_id === $page->id) {
        $block->order = $order++;
        $block->save();
      }
    }
    
    return true;
  }
}