<?php

namespace App\Services\Content;

use App\Models\ContentBlock;
use App\Models\ContentSection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use App\Services\Media\ImageService;
use App\Services\Traits\HandlesTranslations;

class ContentBlockService
{
  use HandlesTranslations;

  protected $imageService;
  protected $translatableFields = ['content'];

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
   * Get all blocks for a section
   */
  public function getBlocks(ContentSection $section): Collection
  {
    return $section->blocks;
  }

  /**
   * Create a new block
   */
  public function create(ContentSection $section, array $data): ContentBlock
  {
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $block = new ContentBlock();
    $block->content_section_id = $section->id;
    $block->type = $data['type'];
    
    // Apply translations
    $this->applyTranslations($block, $data, $this->translatableFields);
    
    // Set other fields
    $block->order = $data['order'] ?? 0;
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
      $block->image = $this->imageService->store(
        $data['image'], 
        'images/uploads/content-blocks'
      );
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
      $this->imageService->delete($block->image);
      $block->image = null;
    }
    // Handle image update
    elseif (isset($data['image']) && $data['image'] instanceof UploadedFile) {
      $block->image = $this->imageService->update(
        $data['image'], 
        $block->image, 
        'images/uploads/content-blocks'
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
      $this->imageService->delete($block->image);
    }
    
    return $block->delete();
  }

  /**
   * Reorder blocks
   */
  public function reorder(ContentSection $section, array $blockIds): bool
  {
    $order = 0;
    
    foreach ($blockIds as $blockId) {
      $block = ContentBlock::find($blockId);
      
      if ($block && $block->content_section_id === $section->id) {
        $block->order = $order++;
        $block->save();
      }
    }
    
    return true;
  }
}