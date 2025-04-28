<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Validation\Rule;

class AttackRange extends Model
{
  use HasFactory;
  use HasTranslations;

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
   * The attributes that are translatable.
   *
   * @var array
   */
  public $translatable = [
    'name',
  ];

  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/uploads/attack-range';
  }

  /**
   * Get the abilities with this range.
   */
  public function abilities(): HasMany
  {
    return $this->hasMany(HeroAbility::class);
  }

  /**
   * Validate if the name is unique for a specific locale
   *
   * @param string $locale
   * @param string $name
   * @param int|null $excludeId
   * @return bool
   */
  public static function isNameUniqueInLocale(string $locale, string $name, ?int $excludeId = null): bool
  {
    $query = static::query()
      ->where(function ($query) use ($locale, $name) {
        $query->whereRaw("JSON_EXTRACT(name, '$.{$locale}') = ?", [$name]);
      });
    
    if ($excludeId) {
      $query->where('id', '!=', $excludeId);
    }
    
    return $query->count() === 0;
  }
}