<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeroAbilityResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'description' => $this->description,
      'attack_type' => $this->attack_type,
      'attack_range_id' => $this->attack_range_id,
      'attack_subtype_id' => $this->attack_subtype_id,
      'area' => $this->area,
      'cost' => $this->cost,
      'parsed_cost' => $this->parsed_cost,
      'total_cost' => $this->total_cost,
    ];
  }
}
