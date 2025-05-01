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
      ['name' => ["en" => "Human", "es" => "Humano"]],
      ['name' => ["en" => "Elf", "es" => "Elfo"]],
      ['name' => ["en" => "Orc", "es" => "Orco"]],
      ['name' => ["en" => "Leonin", "es" => "Leonino"]],
    ];

    foreach ($types as $type) {
      HeroRace::create($type);
    }

    $this->command->info('Razas iniciales creados correctamente.');
  }
}