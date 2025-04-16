<?php

namespace Database\Seeders;

use App\Models\HeroAbility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class HeroAbilitiesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Leer el archivo JSON
    $json = File::get(database_path('data/heroAbilities.json'));
    $abilities = json_decode($json, true);

    // Insertar cada facción
    foreach ($abilities as $abilityData) {
      $ability = new HeroAbility();
      $ability->name = $abilityData['name'];
      $ability->description = $abilityData['description'];
      $ability->attack_range_id = $abilityData['attack_range_id'];
      $ability->attack_subtype_id = $abilityData['attack_subtype_id'];
      $ability->cost = $abilityData['cost'];

      $ability->save();
    }
    $this->command->info("Habilidades iniciales creadas con exito");
  }
}