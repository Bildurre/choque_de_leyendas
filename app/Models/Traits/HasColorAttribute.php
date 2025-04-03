<?php

namespace App\Models\Traits;

trait HasColorAttribute
{
  /**
   * Get RGB representation of the color
   * 
   * @return string
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
   * 
   * @param float $opacity
   * @return string
   */
  public function getRgbaAttribute($opacity = 0.8): string
  {
    $hex = ltrim($this->color, '#');
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    return "rgba($r, $g, $b, $opacity)";
  }

  /**
   * Determine if text should be dark or light based on background color
   * This can be used when creating/updating a model with color
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
   * 
   * @return string
   */
  public function getTextColorClass(): string
  {
    return $this->text_is_dark ? 'text-dark' : 'text-light';
  }
  
  /**
   * Automatically set text color when color attribute is set
   */
  public function setColorAttribute($value)
  {
    $this->attributes['color'] = $value;
    $this->setTextColorBasedOnBackground();
  }
}
