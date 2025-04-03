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
        'description' => 'Los luchadores son maestros del combate cuerpo a cuerpo, dependiendo de su fuerza y resistencia.'
      ],
      [
        'name' => 'Conjurador',
        'description' => 'Los conjuradores utilizan magia y hechizos, dominando fuerzas arcanas y elementales.'
      ]
    ];

    foreach ($superclasses as $superclass) {
      Superclass::create($superclass);
    }

    $this->command->info('Superclases iniciales creadas correctamente.');
  }
}