<?php

namespace App\Http\Requests\Admin\BlockRules;

class CountersListRules
{
  /**
   * Get validation rules for counters list block
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
   * Get validation messages for counters list block
   */
  public static function getMessages(): array
  {
    return [];
  }
}