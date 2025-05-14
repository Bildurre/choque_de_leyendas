<?php

namespace Database\Seeders;

use App\Models\Hero;
use App\Models\HeroAbility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class HeroHeroAbilitiesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Leer el archivo JSON con las relaciones
    $json = File::get(database_path('data/heroHeroAbilities.json'));
    $relations = json_decode($json, true);

    // Array para almacenar los datos a insertar
    $dataToInsert = [];

    // Preparar los datos para la inserción masiva
    foreach ($relations as $relation) {
      $dataToInsert[] = [
        'hero_id' => $relation['hero_id'],
        'hero_ability_id' => $relation['hero_ability_id']
      ];
    }

    // Insertar los datos en la tabla pivot
    DB::table('hero_hero_ability')->insert($dataToInsert);

    $this->command->info("Relaciones entre héroes y habilidades creadas con éxito");
  }
}