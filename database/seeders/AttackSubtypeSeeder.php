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
        'attack_type_id' => 1
      ],
      [
        'name' => 'Perforante',
        'attack_type_id' => 1
      ],
      [
        'name' => 'Contundente',
        'attack_type_id' => 1
      ],
      [
        'name' => 'Fuego',
        'attack_type_id' => 2
      ],
      [
        'name' => 'Electricidad',
        'attack_type_id' => 2
      ],
      [
        'name' => 'Agua',
        'attack_type_id' => 2
      ],
      [
        'name' => 'Tierra',
        'attack_type_id' => 2
      ],
      [
        'name' => 'Aire',
        'attack_type_id' => 2
      ],
      [
        'name' => 'Luz',
        'attack_type_id' => 3
      ],
      [
        'name' => 'Oscuridad',
        'attack_type_id' => 3
      ],
    ];

    foreach ($subtypes as $subtype) {
      AttackSubtype::create($subtype);
    }

    $this->command->info('Subtipos de habilidad iniciales creados correctamente.');
  }
}