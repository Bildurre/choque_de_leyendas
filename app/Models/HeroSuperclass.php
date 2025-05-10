<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class HeroSuperclass extends Model
{
  use HasFactory;
  use HasTranslations;
  use SoftDeletes;

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
    'icon',
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'name',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
    'deleted_at' => 'datetime',
  ];

  /**
   * Get the hero classes that belong to this superclass.
   */
  public function heroClasses()
  {
    return $this->hasMany(HeroClass::class);
  }

  /**
   * Get the card type associated with this superclass (if any).
   */
  public function cardType()
  {
    return $this->hasOne(CardType::class);
  }

  /**
   * Get the directory for storing icons for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/hero-superclasses';
  }

  /**
   * Get the icon URL
   */
  public function getIconUrl(): ?string
  {
    if (!$this->icon) {
      return null;
    }
    
    return asset('storage/' . $this->icon);
  }
}