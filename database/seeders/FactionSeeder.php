<?php

namespace Database\Seeders;

use App\Models\Faction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class FactionSeeder extends Seeder
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
      $faction->name = $factionData['nombre'];
      $faction->lore_text = $factionData['descripcion'];
      $faction->color = $factionData['color'];
      
      // Determinar automáticamente el color del texto
      $faction->setTextColorBasedOnBackground();
      
      // Si existiera un icon_path en los datos, podrías procesarlo aquí
      // $faction->icon = $factionData['icon_path'] ?? null;
      
      $faction->save();
      
      $this->command->info("Facción '{$faction->name}' creada con éxito.");
    }
  }
}