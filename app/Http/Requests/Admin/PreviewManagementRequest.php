<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PreviewManagementRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $action = $this->route()->getActionMethod();

    return match($action) {
      'individualHero' => [
        'hero_id' => 'required|exists:heroes,id',
        'action' => 'required|in:regenerate,delete'
      ],
      'individualCard' => [
        'card_id' => 'required|exists:cards,id',
        'action' => 'required|in:regenerate,delete'
      ],
      'factionAction' => [
        'faction_id' => 'required|exists:factions,id',
        'type' => 'required|in:all,heroes,cards',
        'action' => 'required|in:regenerate,delete'
      ],
      default => []
    };
  }
}