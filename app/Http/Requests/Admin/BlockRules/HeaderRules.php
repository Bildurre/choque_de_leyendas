<?php

namespace App\Http\Requests\Admin\BlockRules;

class HeaderRules
{
  /**
   * Get validation rules for header block
   */
  public static function getRules(): array
  {
    // Por ahora, validamos cualquier dato
    return [
      'title' => ['nullable'],
      'subtitle' => ['nullable'],
    ];
  }
  
  /**
   * Get validation messages for header block
   */
  public static function getMessages(): array
  {
    return [];
  }
}