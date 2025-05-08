<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Block extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'page_id',
        'type',
        'title',
        'subtitle',
        'content',
        'order',
        'background_color',
        'background_image',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'settings' => 'array',
        'order' => 'integer',
    ];

    /**
     * The attributes that are translatable.
     */
    public $translatable = [
        'title',
        'subtitle',
        'content'
    ];

    /**
     * Get the page that owns the block.
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the block type configuration.
     */
    public function getTypeConfig()
    {
        $types = config('blocks.types', []);
        return $types[$this->type] ?? null;
    }

    /**
     * Get the directory for storing images for this model
     */
    public function getImageDirectory(): string
    {
        return 'images/uploads/blocks';
    }

    /**
     * Get the background image URL
     */
    public function getBackgroundImageUrl(): ?string
    {
        if (!$this->background_image) {
            return null;
        }
        
        return asset('storage/' . $this->background_image);
    }

    /**
     * Render the block using the appropriate view
     */
    public function render()
    {
        $config = $this->getTypeConfig();
        
        if (!$config || !isset($config['view'])) {
            return null;
        }
        
        $view = $config['view'];
        
        return view($view, ['block' => $this]);
    }
}