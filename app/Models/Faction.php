<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Traits\HasColorAttribute;
use App\Models\Traits\HasImageAttribute;
use App\Models\Traits\HasSlug;

class Faction extends Model
{
  use HasFactory;
  use HasColorAttribute;
  use HasImageAttribute;
  use HasSlug;

  protected $fillable = [
    'name',
    'lore_text',
    'color',
    'icon',
    'text_is_dark'
  ];

  /**
   * Get the directory for storing images for this model
   * 
   * @return string
   */
  public function getImageDirectory(): string
  {
    return 'images/faction-icons';
  }

  /**
   * Get all heroes belonging to this faction
   */
  public function heroes(): HasMany
  {
    return $this->hasMany(Hero::class);
  }

  /**
   * Get all cards belonging to this faction
   */
  public function cards(): HasMany
  {
    return $this->hasMany(Card::class);
  }

  /**
   * Get all decks belonging to this faction
   */
  public function decks(): HasMany
  {
    return $this->hasMany(Deck::class);
  }
  
  /**
   * Get data for card type chart
   * 
   * @return array
   */
  public function getCardTypesChartData(): array
  {
    if (!$this->relationLoaded('cards')) {
      $this->load('cards');
    }
    
    $types = [
      'equipment' => 'Equipo',
      'technique' => 'Técnica',
      'spell' => 'Hechizo',
      'trick' => 'Ardid',
      'support' => 'Apoyo'
    ];
    
    $data = [];
    $labels = [];
    $backgroundColors = [
      'rgba(255, 99, 132, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(255, 206, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(153, 102, 255, 0.2)'
    ];
    $borderColors = [
      'rgba(255, 99, 132, 1)',
      'rgba(54, 162, 235, 1)',
      'rgba(255, 206, 86, 1)',
      'rgba(75, 192, 192, 1)',
      'rgba(153, 102, 255, 1)'
    ];
    
    $i = 0;
    foreach ($types as $type => $label) {
      $count = $this->cards->where('type', $type)->count();
      $data[] = $count;
      $labels[] = $label;
      $i++;
    }
    
    return [
      'labels' => $labels,
      'data' => $data,
      'backgroundColors' => array_slice($backgroundColors, 0, count($data)),
      'borderColors' => array_slice($borderColors, 0, count($data))
    ];
  }
}