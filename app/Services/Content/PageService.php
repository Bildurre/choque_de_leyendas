<?php

namespace App\Services\Content;

use App\Models\Page;
use App\Services\Media\ImageService;
use App\Services\Traits\HandlesTranslations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
        
        // Generate slugs for all locales if not provided
        $this->generateSlugsForAllLocales($page, $data);
        
        // Set non-translatable fields
        $page->is_published = $data['is_published'] ?? false;
        $page->parent_id = $data['parent_id'] ?? null;
        $page->template = $data['template'] ?? 'default';
        $page->order = $data['order'] ?? 0;
        
        // Handle background image upload
        if (isset($data['background_image']) && $data['background_image'] instanceof UploadedFile) {
          $page->background_image = $this->imageService->store($data['background_image'], $page->getImageDirectory());
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
          $this->imageService->delete($page->background_image);
          $page->background_image = null;
        } elseif (isset($data['background_image']) && $data['background_image'] instanceof UploadedFile) {
            $page->background_image = $this->imageService->update(
                $data['background_image'], 
                $page->background_image, 
                $page->getImageDirectory()
            );
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
        if ($page->background_image) {
            $this->imageService->delete($page->background_image);
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

    /**
     * Generate slugs for all available locales.
     */
    protected function generateSlugsForAllLocales(Page $page, array $data): void
    {
        $locales = config('app.available_locales', ['es', 'en']);
        
        foreach ($locales as $locale) {
            // Skip if slug is already provided for this locale
            if (isset($data['slug'][$locale]) && !empty($data['slug'][$locale])) {
                continue;
            }
            
            // Skip if there is no title to generate slug from
            if (!$page->hasTranslation('title', $locale)) {
                continue;
            }
            
            // Generate slug from title
            $title = $page->getTranslation('title', $locale);
            $slug = Str::slug($title);
            
            // Ensure slug uniqueness
            $suffix = 1;
            $originalSlug = $slug;
            
            while ($this->slugExists($slug, $locale, $page->id)) {
                $slug = $originalSlug . '-' . $suffix;
                $suffix++;
            }
            
            $page->setTranslation('slug', $locale, $slug);
        }
    }

    /**
     * Check if a slug exists for a locale.
     */
    protected function slugExists(string $slug, string $locale, ?int $exceptId = null): bool
    {
        $query = Page::whereJsonContains("slug->{$locale}", $slug);
        
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }
        
        return $query->exists();
    }
}