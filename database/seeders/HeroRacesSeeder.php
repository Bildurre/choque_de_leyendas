<?php

namespace Database\Seeders;

use App\Models\HeroRace;
use Illuminate\Database\Seeder;

class HeroRacesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $types = [
      ['name' => 'Human'],
      ['name' => 'Elf'],
      ['name' => 'Orc'],
      ['name' => 'Leonin'],
      ['name' => 'Draconid']
    ];

    foreach ($types as $type) {
      HeroRace::create($type);
    }

    $this->command->info('Razas iniciales creados correctamente.');
  }
}