<?php

namespace Database\Seeders;

use App\Models\AttackRange;
use Illuminate\Database\Seeder;

class AttackRangesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $ranges = [
      ['name' => ["en" => "Melee", "es" => "Melee"]],
      ['name' => ["en" => "Reach", "es" => "Alcance"]],
      ['name' => ["en" => "Ranged", "es" => "Distante"]],
      ['name' => ["en" => "Aura", "es" => "Aura"]],
      ['name' => ["en" => "Self", "es" => "Propio"]]
    ];

    foreach ($ranges as $range) {
      AttackRange::create($range);
    }

    $this->command->info('Rangos de habilidad iniciales creados correctamente.');
  }
}