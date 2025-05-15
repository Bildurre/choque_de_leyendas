<?php

namespace App\Models;

use App\Models\Traits\HasAdminFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class EquipmentType extends Model
{
  use HasFactory;
  use HasTranslations;
  use SoftDeletes;
  use HasAdminFilters;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'equipment_types';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'category',
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
   * Get the cards associated with this equipment type.
   */
  public function cards()
  {
    return $this->hasMany(Card::class);
  }

  /**
   * Get available categories.
   * 
   * @return array
   */
  public static function getCategories(): array
  {
    return [
      'weapon' => __('equipment_types.categories.weapon'),
      'armor' => __('equipment_types.categories.armor'),
    ];
  }

  /**
   * Get the category name.
   * 
   * @return string
   */
  public function getCategoryNameAttribute(): string
  {
    $categories = self::getCategories();
    return $categories[$this->category] ?? $this->category;
  }
}