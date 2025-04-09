<?php

namespace Database\Seeders;

use App\Models\AttackType;
use Illuminate\Database\Seeder;

class AttackTypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $types = [
      ['name' => 'Marcial'],
      ['name' => 'Proyectil'],
      ['name' => 'Elemental'],
      ['name' => 'Arcano']
    ];

    foreach ($types as $type) {
      AttackType::create($type);
    }

    $this->command->info('Tipos de habilidad iniciales creados correctamente.');
  }
}