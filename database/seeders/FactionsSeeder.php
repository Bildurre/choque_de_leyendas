<?php

namespace Database\Seeders;

use App\Models\Faction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class FactionsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Leer el archivo JSON
    $json = File::get(database_path('data/factions.json'));
    $factions = json_decode($json, true);

    // Insertar cada facción
    foreach ($factions as $factionData) {
      $faction = new Faction();
      $faction->name = $factionData['name'];
      $faction->lore_text = $factionData['lore_text'];
      $faction->color = $factionData['color'];
      
      $faction->setTextColorBasedOnBackground();
      
      $faction->save();
    }

    $this->command->info("Facciones iniciales creadas con éxito.");
  }
}