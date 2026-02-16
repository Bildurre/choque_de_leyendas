<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FactionDeckResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'description' => $this->description,
      'game_mode_id' => $this->game_mode_id,
      'faction_ids' => $this->whenLoaded('factions', function () {
        return $this->factions->pluck('id');
      }),
      'hero_ids' => $this->whenLoaded('heroes', function () {
        return $this->heroes->pluck('id');
      }),
      'cards' => $this->whenLoaded('cards', function () {
        return $this->cards->map(function ($card) {
          return [
            'card_id' => $card->id,
            'copies' => $card->pivot->copies,
          ];
        });
      }),
      'icon_url' => $this->icon ? asset('storage/' . $this->icon) : null,
    ];
  }
}
