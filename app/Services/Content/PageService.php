<?php

namespace App\Services\Content;

use App\Models\Page;
use App\Services\Media\ImageService;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class PageService
{
    use HandlesTranslations;
    
    protected $imageService;
    protected $translatableFields = ['title', 'description', 'meta_title', 'meta_description', 'slug'];

    /**
     * Create a new service instance.
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Create a new page with the provided data.
     */
    public function create(array $data): Page
    {
        // Process translatable fields
        $data = $this->processTranslatableFields($data, $this->translatableFields);
        
        $page = new Page();
        
        // Apply translations
        $this->applyTranslations($page, $data, $this->translatableFields);
                
        // Set non-translatable fields
        $page->is_published = $data['is_published'] ?? false;
        $page->parent_id = $data['parent_id'] ?? null;
        $page->template = $data['template'] ?? 'default';
        $page->order = $data['order'] ?? 0;
        
        // Handle background image upload
        if (isset($data['background_image']) && $data['background_image'] instanceof UploadedFile) {
          $page->storeImage($data['background_image']);
        }
        
        $page->save();
        
        return $page;
    }

    /**
     * Update an existing page with the provided data.
     */
    public function update(Page $page, array $data): Page
    {
        // Process translatable fields
        $data = $this->processTranslatableFields($data, $this->translatableFields);
        
        // Apply translations
        $this->applyTranslations($page, $data, $this->translatableFields);
        
        // Generate slugs for all locales if not provided
        $this->generateSlugsForAllLocales($page, $data);
        
        // Update non-translatable fields
        $page->is_published = $data['is_published'] ?? $page->is_published;
        $page->parent_id = $data['parent_id'] ?? $page->parent_id;
        $page->template = $data['template'] ?? $page->template;
        $page->order = $data['order'] ?? $page->order;
        
        // Handle background image updates
        if (isset($data['remove_background_image']) && $data['remove_background_image']) {
          $page->deleteImage();
        } elseif (isset($data['background_image']) && $data['background_image'] instanceof UploadedFile) {
          $page->storeImage($data['background_image']);
        }
        
        $page->save();
        
        return $page;
    }

    /**
     * Delete a page and its related resources.
     */
    public function delete(Page $page): bool
    {
        // Delete background image if exists
        if ($page->hasImage()) {
          $page->deleteImage();
        }
        
        // Update any child pages to have no parent
        $page->children()->update(['parent_id' => null]);
        
        return $page->delete();
    }

    /**
     * Get available templates for pages.
     */
    public function getAvailableTemplates(): array
    {
        $templates = [];
        $path = resource_path('views/content/templates');
        
        // Ensure the directory exists
        if (!File::exists($path)) {
            return ['default' => 'Default'];
        }
        
        // Get all blade files in the templates directory
        $files = File::files($path);
        
        foreach ($files as $file) {
            $name = str_replace('.blade.php', '', $file->getFilename());
            $templates[$name] = ucfirst(str_replace('_', ' ', $name));
        }
        
        return $templates;
    }
}