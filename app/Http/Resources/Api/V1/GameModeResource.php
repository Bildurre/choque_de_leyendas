<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameModeResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'description' => $this->description,
      'deck_configuration' => $this->whenLoaded('deckConfiguration', function () {
        return [
          'min_cards' => $this->deckConfiguration->min_cards,
          'max_cards' => $this->deckConfiguration->max_cards,
          'max_copies_per_card' => $this->deckConfiguration->max_copies_per_card,
          'required_heroes' => $this->deckConfiguration->required_heroes,
        ];
      }),
    ];
  }
}
