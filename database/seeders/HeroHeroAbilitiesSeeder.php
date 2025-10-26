<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class HeroHeroAbilitiesSeeder extends Seeder
{
  public function run(): void
  {
    // Lee JSON
    $json = File::get(database_path('data/heroHeroAbilities.json'));
    $relations = json_decode($json, true);

    // Si el JSON viene con 'position', la usamos; si no, contamos por héroe
    $posByHero = [];
    $dataToInsert = [];

    foreach ($relations as $rel) {
      $heroId = (int) $rel['hero_id'];
      $abilityId = (int) $rel['hero_ability_id'];

      // si no viene position en el JSON, la asignamos incremental
      $position = isset($rel['position'])
        ? (int) $rel['position']
        : (($posByHero[$heroId] ?? 0) + 1);

      $posByHero[$heroId] = $position;

      $dataToInsert[] = [
        'hero_id' => $heroId,
        'hero_ability_id' => $abilityId,
        'position' => $position,
        'created_at' => now(),
        'updated_at' => now(),
      ];
    }

    // Inserción masiva (si la tabla ya tiene datos, decide si truncas antes)
    // DB::table('hero_hero_ability')->truncate();
    DB::table('hero_hero_ability')->insert($dataToInsert);

    $this->command?->info("Relaciones héroe ↔ habilidad insertadas con posición.");
  }
}
