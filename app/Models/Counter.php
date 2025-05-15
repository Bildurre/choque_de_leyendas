<?php

namespace App\Models;

use App\Models\Traits\HasAdminFilters;
use App\Models\Traits\HasImageAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Counter extends Model
{
  use HasFactory, SoftDeletes, HasTranslations, HasImageAttribute, HasAdminFilters;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'effect',
    'type',
    'icon'
  ];

  /**
   * The attributes that are translatable.
   *
   * @var array<int, string>
   */
  public $translatable = [
    'name',
    'effect'
  ];

  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/counters';
  }

  /**
   * Get the type name attribute
   * 
   * @return string
   */
  public function getTypeNameAttribute(): string
  {
    return __('counters.types.' . $this->type);
  }

  /**
   * Get array of available counter types
   * 
   * @return array
   */
  public static function getTypes(): array
  {
    return [
      'boon' => __('counters.types.boon'),
      'bane' => __('counters.types.bane')
    ];
  }
}