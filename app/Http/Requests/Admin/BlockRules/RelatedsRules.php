<?php

namespace App\Http\Requests\Admin\BlockRules;

class RelatedsRules
{
  /**
   * Get validation rules for relateds block
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
   * Get validation messages for relateds block
   */
  public static function getMessages(): array
  {
    return [];
  }
}