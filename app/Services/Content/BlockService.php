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
        // Process content for CTA blocks
        if ($data['type'] === 'cta' && isset($data['content'])) {
            $data = $this->processCTAContent($data);
        }
        
        // Process translatable fields
        $data = $this->processTranslatableFields($data, $this->translatableFields);
        
        $block = new Block();
        
        // Apply translations
        $this->applyTranslations($block, $data, $this->translatableFields);
        
        // Set non-translatable fields
        $block->page_id = $data['page_id'];
        $block->type = $data['type'];
        $block->order = $data['order'] ?? 0;
        $block->background_color = $data['background_color'] ?? null;
        
        // Handle settings
        if (isset($data['settings']) && is_array($data['settings'])) {
            $block->settings = $data['settings'];
        }
        
        // Handle image upload using HasImageAttribute trait
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $block->storeImage($data['image']);
        }
        
        $block->save();
        
        return $block;
    }

    /**
     * Update an existing block.
     */
    public function update(Block $block, array $data): Block
    {
        // Process content for CTA blocks
        if ($block->type === 'cta' && isset($data['content'])) {
            $data = $this->processCTAContent($data);
        }
        
        // Process translatable fields
        $data = $this->processTranslatableFields($data, $this->translatableFields);
        
        // Apply translations
        $this->applyTranslations($block, $data, $this->translatableFields);
        
        // Update non-translatable fields
        if (isset($data['order'])) {
            $block->order = $data['order'];
        }
        
        if (isset($data['background_color'])) {
            $block->background_color = $data['background_color'];
        }
        
        // Handle settings
        if (isset($data['settings']) && is_array($data['settings'])) {
            $block->settings = $data['settings'];
        }
        
        // Handle image updates using HasImageAttribute trait
        if (isset($data['remove_image']) && $data['remove_image']) {
            $block->deleteImage();
        } elseif (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $block->storeImage($data['image']);
        }
        
        $block->save();
        
        return $block;
    }

    /**
     * Delete a block.
     */
    public function delete(Block $block): bool
    {
        // Delete any associated images using HasImageAttribute trait
        if ($block->hasImage()) {
            $block->deleteImage();
        }
        
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
}