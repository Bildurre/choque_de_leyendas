<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'lore_text' => $this->lore_text,
      'faction_id' => $this->faction_id,
      'card_type_id' => $this->card_type_id,
      'card_subtype_id' => $this->card_subtype_id,
      'equipment_type_id' => $this->equipment_type_id,
      'attack_range_id' => $this->attack_range_id,
      'attack_subtype_id' => $this->attack_subtype_id,
      'hero_ability_id' => $this->hero_ability_id,
      'attack_type' => $this->attack_type,
      'hands' => $this->hands,
      'cost' => $this->cost,
      'parsed_cost' => $this->parsed_cost,
      'total_cost' => $this->total_cost,
      'effect' => $this->effect,
      'restriction' => $this->restriction,
      'area' => $this->area,
      'is_unique' => $this->is_unique,
    ];
  }
}
