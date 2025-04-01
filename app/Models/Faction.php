<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faction extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'lore_text',
    'color',
    'icon',
    'text_is_dark'
  ];

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
   * Determine if text should be dark or light based on background color
   * This can be used when creating/updating a faction
   */
  public function setTextColorBasedOnBackground(): void
  {
    // Elimina el # si existe
    $hex = ltrim($this->color, '#');
    
    // Convierte el color hexadecimal a RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Calcula la luminosidad según la fórmula YIQ
    // https://24ways.org/2010/calculating-color-contrast/
    $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
    
    // Si la luminosidad es mayor que 128, se considera un color claro
    // y el texto debe ser oscuro
    $this->text_is_dark = $yiq >= 128;
  }

  /**
   * Get text color class based on background color
   */
  public function getTextColorClass(): string
  {
    return $this->text_is_dark ? 'text-dark' : 'text-light';
  }

  /**
   * Get RGB representation of the color
   */
  public function getRgbAttribute(): string
  {
    $hex = ltrim($this->color, '#');
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    return "rgb($r, $g, $b)";
  }

  /**
   * Get RGBA representation with transparency
   */
  public function getRgbaAttribute($opacity = 0.8): string
  {
    $hex = ltrim($this->color, '#');
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    return "rgba($r, $g, $b, $opacity)";
  }
}