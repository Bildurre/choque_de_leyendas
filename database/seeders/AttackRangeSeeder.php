<?php

namespace Database\Seeders;

use App\Models\AttackRange;
use Illuminate\Database\Seeder;

class AttackRangeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $ranges = [
      ['name' => 'Cuerpo a cuerpo'],
      ['name' => 'A Rango'],
      ['name' => 'A Distancia'],
      ['name' => 'Aura'],
      ['name' => 'Contacto'],
      ['name' => 'Propio']
    ];

    foreach ($ranges as $range) {
      AttackRange::create($range);
    }

    $this->command->info('Rangos de habilidad iniciales creados correctamente.');
  }
}