<?php

namespace Database\Seeders;

use App\Models\Hero;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class HeroesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Leer el archivo JSON
    $json = File::get(database_path('data/heroes.json'));
    $heroes = json_decode($json, true);

    // Insertar cada facción
    foreach ($heroes as $heroData) {
      $hero = new Hero();
      $hero->name = $heroData['name'];
      $hero->lore_text = $heroData['lore_text'];
      $hero->passive_name = $heroData['passive_name'];
      $hero->passive_description = $heroData['passive_description'];
      $hero->faction_id = $heroData['faction_id'];
      $hero->hero_race_id = $heroData['hero_race_id'];
      $hero->hero_class_id = $heroData['hero_class_id'];
      $hero->gender = $heroData['gender'];
      $hero->agility = $heroData['agility'];
      $hero->mental = $heroData['mental'];
      $hero->will = $heroData['will'];
      $hero->strength = $heroData['strength'];
      $hero->armor = $heroData['armor'];
            
      $hero->save();
    }

    $this->command->info("Héroes iniciales creadas con éxito.");
  }
}