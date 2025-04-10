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
      ['name' => 'Saboteador'],
      ['name' => 'Adalid']
    ];

    foreach ($heroSuperclasses as $heroSuperclass) {
      HeroSuperclass::create($heroSuperclass);
    }

    $this->command->info('Superclases iniciales creadas correctamente.');
  }
}