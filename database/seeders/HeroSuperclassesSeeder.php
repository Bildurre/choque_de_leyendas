<?php

namespace Database\Seeders;

use App\Models\HeroSuperclass;
use Illuminate\Database\Seeder;

class HeroSuperclassesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $heroSuperclasses = [
      ['name' => ["en" => "Fighter", "es" => "Combatiente"]],
      ['name' => ["en" => "Caster", "es" => "Conjurador"]],
      ['name' => ["en" => "Saboteur", "es" => "Saboteador"]],
      ['name' => ["en" => "Chosen", "es" => "Elegido"]],
    ];

    foreach ($heroSuperclasses as $heroSuperclass) {
      HeroSuperclass::create($heroSuperclass);
    }

    $this->command->info('Superclases iniciales creadas correctamente.');
  }
}