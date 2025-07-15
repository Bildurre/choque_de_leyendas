<?php

namespace App\Http\Requests\Admin\BlockRules;

class TextCardRules
{
  /**
   * Get validation rules for text card block
   */
  public static function getRules(): array
  {
    // Por ahora, validamos cualquier dato
    return [
      'title' => ['nullable'],
      'subtitle' => ['nullable'],
      'content' => ['nullable'],
    ];
  }
  
  /**
   * Get validation messages for text card block
   */
  public static function getMessages(): array
  {
    return [];
  }
}