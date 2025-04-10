<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\HasImageAttribute;

class HeroSuperclass extends Model
{
  use HasFactory;
  use HasImageAttribute;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'hero_superclasses';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'icon'
  ];

  /**
   * Get the hero classes that belong to this superclass.
   */
  public function heroClasses(): HasMany
  {
    return $this->hasMany(HeroClass::class, 'hero_superclass_id');
  }

  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/uploads/hero-superclass';
  }
}