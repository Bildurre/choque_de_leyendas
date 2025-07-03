<?php

namespace App\Http\Requests\Admin\BlockRules;

class TextRules
{
  /**
   * Get validation rules for text block
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
   * Get validation messages for text block
   */
  public static function getMessages(): array
  {
    return [];
  }
}