<?php

namespace App\Models;

use App\Models\Traits\HasColorAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\HasSlug;
use App\Models\Traits\HasImageAttribute;

class Superclass extends Model
{
  use HasFactory;
  use HasColorAttribute;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'description',
    'color',
    'text_is_dark'
  ];

  /**
   * Get the hero classes that belong to this superclass.
   */
  public function heroClasses(): HasMany
  {
    return $this->hasMany(HeroClass::class);
  }

  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'superclass-icons';
  }
}