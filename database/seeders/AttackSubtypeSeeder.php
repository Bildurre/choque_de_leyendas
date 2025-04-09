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
      ['name' => 'Cortante'],
      ['name' => 'Perforante'],
      ['name' => 'Contundente'],
      ['name' => 'Lacerante'],
      ['name' => 'Fuego'],
      ['name' => 'Agua'],
      ['name' => 'Electricidad'],
      ['name' => 'Aire'],
      ['name' => 'Tierra'],
      ['name' => 'Luz'],
      ['name' => 'Oscuridad'],
      ['name' => 'Arcano'],
      ['name' => 'Psíquico']
    ];

    foreach ($subtypes as $subtype) {
      AttackSubtype::create($subtype);
    }

    $this->command->info('Subtipos de habilidad iniciales creados correctamente.');
  }
}