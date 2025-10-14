<?php

namespace App\Models;

use App\Models\Traits\HasFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class CardSubtype extends Model
{
  use HasFactory;
  use HasTranslations;
  use SoftDeletes;
  use HasFilters;

  protected $fillable = [
    'name',
  ];

  public $translatable = [
    'name',
  ];

  protected $casts = [
    'deleted_at' => 'datetime',
  ];

  /**
   * Get fields that can be searched
   *
   * @return array
   */
  public function getAdminSearchable(): array
  {
    return [];
  }

  /**
   * Get fields that can be filtered
   *
   * @return array
   */
  public function getAdminFilterable(): array
  {
    return [];
  }

  /**
   * Get fields that can be sorted
   *
   * @return array
   */
  public function getAdminSortable(): array
  {
    return [
      [
        'field' => 'name',
        'label' => __('entities.card_subtypes.name')
      ],
      [
        'field' => 'created_at',
        'label' => __('common.created_at')
      ]
    ];
  }

  /**
   * Get the cards that belong to this subtype.
   */
  public function cards()
  {
    return $this->hasMany(Card::class);
  }
}