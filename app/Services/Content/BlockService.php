<?php

namespace App\Services\Content;

use App\Models\Block;
use App\Services\Media\ImageService;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Http\UploadedFile;

class BlockService
{
    use HandlesTranslations;
    
    protected $imageService;
    protected $translatableFields = ['title', 'subtitle', 'content'];

    /**
     * Create a new service instance.
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Create a new block.
     */
    public function create(array $data): Block
    {
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
        
        // Handle background image upload
        if (isset($data['background_image']) && $data['background_image'] instanceof UploadedFile) {
            $block->background_image = $this->imageService->store($data['background_image'], $block->getImageDirectory());
        }
        
        $block->save();
        
        return $block;
    }

    /**
     * Update an existing block.
     */
    public function update(Block $block, array $data): Block
    {
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
        
        // Handle background image updates
        if (isset($data['remove_background_image']) && $data['remove_background_image']) {
            $this->imageService->delete($block->background_image);
            $block->background_image = null;
        } elseif (isset($data['background_image']) && $data['background_image'] instanceof UploadedFile) {
            $block->background_image = $this->imageService->update(
                $data['background_image'], 
                $block->background_image, 
                $block->getImageDirectory()
            );
        }
        
        $block->save();
        
        return $block;
    }

    /**
     * Delete a block.
     */
    public function delete(Block $block): bool
    {
        // Delete any associated images
        if ($block->background_image) {
            $this->imageService->delete($block->background_image);
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
}