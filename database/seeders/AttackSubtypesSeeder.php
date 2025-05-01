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
        'name' => ["en" => "Slashing", "es" => "Cortante"],
        'type' => 'physical'
      ],
      [
        'name' => ["en" => "Piercing", "es" => "Perforante"],
        'type' => 'physical'
      ],
      [
        'name' => ["en" => "Crushing", "es" => "Contundente"],
        'type' => 'physical'
      ],
      [
        'name' => ["en" => "Rending", "es" => "Lacerante"],
        'type' => 'physical'
      ],
      [
        'name' => ["en" => "Fire", "es" => "Fuego"],
        'type' => 'magical'
      ],
      [
        'name' => ["en" => "Lightning", "es" => "Electricidad"],
        'type' => 'magical'
      ],
      [
        'name' => ["en" => "Water", "es" => "Agua"],
        'type' => 'magical'
      ],
      [
        'name' => ["en" => "Earth", "es" => "Tierra"],
        'type' => 'magical'
      ],
      [
        'name' => ["en" => "Air", "es" => "Aire"],
        'type' => 'magical'
      ],
      [
        'name' => ["en" => "Light", "es" => "Luz"],
        'type' => 'magical'
      ],
      [
        'name' => ["en" => "Darkness", "es" => "Oscuridad"],
        'type' => 'magical'
      ],
    ];

    foreach ($subtypes as $subtype) {
      AttackSubtype::create($subtype);
    }

    $this->command->info('Subtipos de habilidad iniciales creados correctamente.');
  }
}