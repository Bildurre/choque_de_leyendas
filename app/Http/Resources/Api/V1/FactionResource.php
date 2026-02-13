<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FactionResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'lore_text' => $this->lore_text,
      'color' => $this->color,
      'icon_url' => $this->icon ? asset('storage/' . $this->icon) : null,
      'text_is_dark' => $this->text_is_dark,
      'is_mercenaries' => $this->isMercenaries(),
    ];
  }
}
