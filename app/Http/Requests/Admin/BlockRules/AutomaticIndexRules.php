<?php

namespace App\Http\Requests\Admin\BlockRules;

class HAutomaticIndexRules
{
  /**
   * Get validation rules for automatic index block
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
   * Get validation messages for automatic index block
   */
  public static function getMessages(): array
  {
    return [];
  }
}