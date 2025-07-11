<?php

namespace App\Services\Content;

use App\Models\Block;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Http\UploadedFile;

class BlockService
{
  use HandlesTranslations;
  
  protected $translatableFields = ['title', 'subtitle', 'content'];

  /**
   * Create a new service instance.
   */
  public function __construct()
  {
    // Sin dependencia de ImageService
  }

  /**
   * Create a new block.
   */
  public function create(array $data): Block
  {
    // Process content based on block type
    $data = $this->processBlockContent($data);
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    $block = new Block();
    
    // Apply translations
    $this->applyTranslations($block, $data, $this->translatableFields);
    
    // Set non-translatable fields
    $block->page_id = $data['page_id'];
    $block->type = $data['type'];
    $block->order = $data['order'] ?? 0;
    // Fix: Check if is_printable exists in data array
    $block->is_printable = array_key_exists('is_printable', $data) ? (bool)$data['is_printable'] : true;
    $block->background_color = $data['background_color'] ?? null;
    
    // Handle settings
    if (isset($data['settings']) && is_array($data['settings'])) {
      $block->settings = $data['settings'];
    }
    
    // Save the block first to get an ID (needed for image storage)
    $block->save();
    
    // Handle multilingual image uploads
    $this->handleMultilingualImageUploads($block, $data);
    
    return $block;
  }

  /**
   * Update an existing block.
   */
  public function update(Block $block, array $data): Block
  {
    // Process content based on block type
    $data = $this->processBlockContent($data, $block->type);
    
    // Process translatable fields
    $data = $this->processTranslatableFields($data, $this->translatableFields);
    
    // Apply translations
    $this->applyTranslations($block, $data, $this->translatableFields, true);
    
    // Update non-translatable fields
    if (isset($data['order'])) {
      $block->order = $data['order'];
    }
    
    // Fix: Check if is_printable exists in data array
    if (array_key_exists('is_printable', $data)) {
      $block->is_printable = (bool)$data['is_printable'];
    }
    
    if (isset($data['background_color'])) {
      $block->background_color = $data['background_color'];
    }
    
    // Handle settings
    if (isset($data['settings']) && is_array($data['settings'])) {
      $block->settings = $data['settings'];
    }
    
    // Handle multilingual image uploads and removals
    $this->handleMultilingualImageUploads($block, $data);
    
    $block->save();
    
    return $block;
  }

  /**
   * Delete a block.
   */
  public function delete(Block $block): bool
  {
    // The HasMultilingualImageAttribute trait will automatically delete all images
    // when the model is deleted through the bootHasMultilingualImageAttribute method
    return $block->delete();
  }

  /**
   * Reorder blocks for a page.
   */
  public function reorderBlocks(int $pageId, array $blockIds): bool
  {
    $order = 0;
    
    foreach ($blockIds as $blockId) {
      $block = Block::where('page_id', $pageId)
        ->where('id', $blockId)
        ->first();
        
      if ($block) {
        $block->order = $order;
        $block->save();
        $order++;
      }
    }
    
    return true;
  }

  /**
   * Get block types.
   */
  public function getBlockTypes(): array
  {
    return config('blocks.types', []);
  }
  
  /**
   * Process block content based on type
   */
  protected function processBlockContent(array $data, ?string $blockType = null): array
  {
    $type = $blockType ?? $data['type'] ?? null;
    
    if (!$type || !isset($data['content'])) {
      return $data;
    }
    
    switch ($type) {
      case 'cta':
        return $this->processCTAContent($data);
        
      case 'relateds':
        return $this->processRelatedsContent($data);
        
      default:
        return $data;
    }
  }
  
  /**
   * Process CTA content structure
   */
  protected function processCTAContent(array $data): array
  {
    if (isset($data['content']['text']) && 
        isset($data['content']['button_text']) && 
        isset($data['content']['button_link'])) {
      
      $processedContent = [];
      $locales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
      
      foreach ($locales as $locale) {
        $processedContent[$locale] = [
          'text' => $data['content']['text'][$locale] ?? '',
          'button_text' => $data['content']['button_text'][$locale] ?? '',
          'button_link' => $data['content']['button_link'][$locale] ?? ''
        ];
      }
      
      $data['content'] = $processedContent;
    }
    
    return $data;
  }
  
  /**
   * Process Relateds content structure
   */
  protected function processRelatedsContent(array $data): array
  {
    if (isset($data['content']['button_text'])) {
      $processedContent = [];
      $locales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
      
      foreach ($locales as $locale) {
        $processedContent[$locale] = [
          'button_text' => $data['content']['button_text'][$locale] ?? ''
        ];
      }
      
      $data['content'] = $processedContent;
    }
    
    return $data;
  }
  
  /**
   * Handle multilingual image uploads and removals
   */
  protected function handleMultilingualImageUploads(Block $block, array $data): void
  {
    $locales = array_keys(config('laravellocalization.supportedLocales', ['es' => []]));
    
    foreach ($locales as $locale) {
      // Check for image removal
      if (isset($data["remove_image_{$locale}"]) && $data["remove_image_{$locale}"]) {
        $block->deleteMultilingualImage($locale);
      }
      
      // Check for image upload
      if (isset($data["image_{$locale}"]) && $data["image_{$locale}"] instanceof UploadedFile) {
        $block->storeMultilingualImage($data["image_{$locale}"], $locale);
      }
    }
  }
}