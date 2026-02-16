<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeroResource extends JsonResource
{
  public function toArray(Request $request): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'lore_text' => $this->lore_text,
      'passive_name' => $this->passive_name,
      'passive_description' => $this->passive_description,
      'faction_id' => $this->faction_id,
      'hero_race_id' => $this->hero_race_id,
      'hero_class_id' => $this->hero_class_id,
      'gender' => $this->gender,
      'agility' => $this->agility,
      'mental' => $this->mental,
      'will' => $this->will,
      'strength' => $this->strength,
      'armor' => $this->armor,
      'health' => $this->health,
      'total_attributes' => $this->total_attributes,
      'abilities' => $this->whenLoaded('heroAbilities', function () {
        return $this->heroAbilities->map(function ($ability) {
          return [
            'id' => $ability->id,
            'position' => $ability->pivot->position,
          ];
        });
      }),
      'image_url' => $this->image ? asset('storage/' . $this->image) : null,
      'preview_image_url' => $this->preview_image ? collect(json_decode($this->preview_image, true))->map(fn($path) => asset('storage/' . $path)) : null,
    ];
  }
}
