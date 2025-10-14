<?php

namespace Database\Seeders;

use App\Models\AttackType;
use App\Models\AttackSubtype;
use Illuminate\Database\Seeder;

class AttackSubtypesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    
    $subtypes = [
      [
        'name' => ["en" => "Slashing", "es" => "Cortante"]
      ],
      [
        'name' => ["en" => "Piercing", "es" => "Perforante"]
      ],
      [
        'name' => ["en" => "Crushing", "es" => "Contundente"]
      ],
      [
        'name' => ["en" => "Rending", "es" => "Lacerante"]
      ],
      [
        'name' => ["en" => "Fire", "es" => "Fuego"]
      ],
      [
        'name' => ["en" => "Lightning", "es" => "Electricidad"]
      ],
      [
        'name' => ["en" => "Water", "es" => "Agua"]
      ],
      [
        'name' => ["en" => "Earth", "es" => "Tierra"]
      ],
      [
        'name' => ["en" => "Air", "es" => "Aire"]
      ],
      [
        'name' => ["en" => "Light", "es" => "Luz"]
      ],
      [
        'name' => ["en" => "Darkness", "es" => "Oscuridad"]
      ],
    ];

    foreach ($subtypes as $subtype) {
      AttackSubtype::create($subtype);
    }

    $this->command->info('Subtipos de habilidad iniciales creados correctamente.');
  }
}