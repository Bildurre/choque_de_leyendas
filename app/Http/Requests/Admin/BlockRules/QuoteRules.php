<?php

namespace App\Http\Requests\Admin\BlockRules;

class QuoteRules
{
  /**
   * Get validation rules for CTA block
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
   * Get validation messages for CTA block
   */
  public static function getMessages(): array
  {
    return [];
  }
}