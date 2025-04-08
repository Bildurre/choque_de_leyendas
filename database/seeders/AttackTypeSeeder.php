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
      [
        'name' => 'Físico',
        'description' => 'Habilidades que requieren fuerza física o destreza corporal',
        'color' => '#f53d3d'
      ],
      [
        'name' => 'Mágico',
        'description' => 'Habilidades que usan magia o poderes místicos',
        'color' => '#3d3df5'
      ],
      [
        'name' => 'Especial',
        'description' => 'Habilidades únicas o raras que no encajan en otras categorías',
        'color' => '#3df53d'
      ]
    ];

    foreach ($types as $type) {
      AttackType::create($type);
    }

    $this->command->info('Tipos de habilidad iniciales creados correctamente.');
  }
}