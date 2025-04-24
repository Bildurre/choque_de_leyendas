<?php

namespace Database\Seeders;

use App\Models\AttackType;
use App\Models\AttackSubtype;
use Illuminate\Database\Seeder;

class AttackSubtypeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    
    $subtypes = [
      [
        'name' => 'Cortante',
        'type' => 'physical'
      ],
      [
        'name' => 'Perforante',
        'type' => 'physical'
      ],
      [
        'name' => 'Contundente',
        'type' => 'physical'
      ],
      [
        'name' => 'Fuego',
        'type' => 'magical'
      ],
      [
        'name' => 'Electricidad',
        'type' => 'magical'
      ],
      [
        'name' => 'Agua',
        'type' => 'magical'
      ],
      [
        'name' => 'Tierra',
        'type' => 'magical'
      ],
      [
        'name' => 'Aire',
        'type' => 'magical'
      ],
      [
        'name' => 'Luz',
        'type' => 'magical'
      ],
      [
        'name' => 'Oscuridad',
        'type' => 'magical'
      ],
    ];

    foreach ($subtypes as $subtype) {
      AttackSubtype::create($subtype);
    }

    $this->command->info('Subtipos de habilidad iniciales creados correctamente.');
  }
}