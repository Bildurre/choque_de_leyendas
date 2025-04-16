<?php

namespace Database\Seeders;

use App\Models\HeroSuperclass;
use Illuminate\Database\Seeder;

class HeroSuperclassSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $heroSuperclasses = [
      ['name' => 'Combatiente'],
      ['name' => 'Conjurador'],
      ['name' => 'Adalid'],
      ['name' => 'Saboteador']
    ];

    foreach ($heroSuperclasses as $heroSuperclass) {
      HeroSuperclass::create($heroSuperclass);
    }

    $this->command->info('Superclases iniciales creadas correctamente.');
  }
}