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
      [
        'name' => 'Cuerpo a cuerpo',
        'description' => 'Habilidades que requieren contacto directo con el objetivo',
      ],
      [
        'name' => 'Corto',
        'description' => 'Habilidades que afectan a objetivos cercanos',
      ],
      [
        'name' => 'Medio',
        'description' => 'Habilidades que afectan a objetivos a distancia media',
      ],
      [
        'name' => 'Largo',
        'description' => 'Habilidades que afectan a objetivos lejanos',
      ],
      [
        'name' => 'Global',
        'description' => 'Habilidades que afectan a todo el campo de batalla',
      ],
      [
        'name' => 'Personal',
        'description' => 'Habilidades que solo afectan al usuario',
      ],
      [
        'name' => 'Área',
        'description' => 'Habilidades que afectan a múltiples objetivos en un área',
      ]
    ];

    foreach ($ranges as $range) {
      AttackRange::create($range);
    }

    $this->command->info('Rangos de habilidad iniciales creados correctamente.');
  }
}