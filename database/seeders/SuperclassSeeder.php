<?php

namespace Database\Seeders;

use App\Models\Superclass;
use Illuminate\Database\Seeder;

class SuperclassSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $superclasses = [
      [
        'name' => 'Luchador',
        'description' => 'Los luchadores son maestros del combate cuerpo a cuerpo, dependiendo de su fuerza y resistencia.',
        'color' => '#f53d3d'
      ],
      [
        'name' => 'Conjurador',
        'description' => 'Los conjuradores utilizan magia y hechizos, dominando fuerzas arcanas y elementales.',
        'color' => '#3d3df5'
      ]
    ];

    foreach ($superclasses as $superclass) {
      Superclass::create($superclass);
    }

    $this->command->info('Superclases iniciales creadas correctamente.');
  }
}