<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CardsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Leer el archivo JSON
    $json = File::get(database_path('data/terikCards.json'));
    $factionCards[0] = json_decode($json, true);
    $json = File::get(database_path('data/thenanCards.json'));
    $factionCards[1] = json_decode($json, true);
    $faction_id = 0;

    foreach($factionCards as $cards) {
      $faction_id++;
      foreach ($cards as $data) {
        $card = new Card();
        $card->name = $data['name'];
        $card->image = $data['image'];
        $card->lore_text = $data['lore_text'];
        $card->epic_quote = $data['epic_quote'];
        $card->faction_id = $faction_id;
        $card->card_type_id = $data['card_type_id'];
        $card->equipment_type_id = $data['equipment_type_id'] ?? null;
        $card->attack_range_id = $data['attack_range_id'] ?? null;
        $card->attack_subtype_id = $data['attack_subtype_id'] ?? null;
        $card->hero_ability_id = $data['hero_ability_id'] ?? null;
        $card->hands = $data['hands'] ?? null;
        $card->cost = $data['cost'] ?? null;
        $card->effect = $data['effect'];
        $card->restriction = $data['restriction'] ?? null;
        $card->area = $data['area'] ?? false;
        $card->is_published = true;
              
        $card->save();
      }
    }

    $this->command->info("Cartas iniciales creadas con éxito.");
  }
}