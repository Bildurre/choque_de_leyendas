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
    $physicalType = AttackType::where('name', 'Físico')->first();
    $magicalType = AttackType::where('name', 'Mágico')->first();
    $specialType = AttackType::where('name', 'Especial')->first();

    if (!$physicalType || !$magicalType || !$specialType) {
      $this->command->error('Los tipos de habilidad no existen. Ejecuta primero AttackTypeSeeder.');
      return;
    }

    $subtypes = [
      // Físicos
      [
        'name' => 'Cortante',
        'description' => 'Habilidades con armas de filo',
        'attack_type_id' => $physicalType->id,
        'color' => '#ff7f7f'
      ],
      [
        'name' => 'Contundente',
        'description' => 'Habilidades con armas contundentes',
        'attack_type_id' => $physicalType->id,
        'color' => '#ff9f9f'
      ],
      [
        'name' => 'Perforante',
        'description' => 'Habilidades con armas punzantes',
        'attack_type_id' => $physicalType->id,
        'color' => '#ffbfbf'
      ],
      
      // Mágicos
      [
        'name' => 'Fuego',
        'description' => 'Habilidades basadas en el elemento fuego',
        'attack_type_id' => $magicalType->id,
        'color' => '#7f7fff'
      ],
      [
        'name' => 'Agua',
        'description' => 'Habilidades basadas en el elemento agua',
        'attack_type_id' => $magicalType->id,
        'color' => '#9f9fff'
      ],
      [
        'name' => 'Aire',
        'description' => 'Habilidades basadas en el elemento aire',
        'attack_type_id' => $magicalType->id,
        'color' => '#bfbfff'
      ],
      [
        'name' => 'Tierra',
        'description' => 'Habilidades basadas en el elemento tierra',
        'attack_type_id' => $magicalType->id,
        'color' => '#dfdfff'
      ],
      
      // Especiales
      [
        'name' => 'Sanación',
        'description' => 'Habilidades de curación y restauración',
        'attack_type_id' => $specialType->id,
        'color' => '#7fff7f'
      ],
      [
        'name' => 'Apoyo',
        'description' => 'Habilidades que mejoran a los aliados',
        'attack_type_id' => $specialType->id,
        'color' => '#9fff9f'
      ],
      [
        'name' => 'Control',
        'description' => 'Habilidades para controlar el campo de batalla',
        'attack_type_id' => $specialType->id,
        'color' => '#bfffbf'
      ]
    ];

    foreach ($subtypes as $subtype) {
      AttackSubtype::create($subtype);
    }

    $this->command->info('Subtipos de habilidad iniciales creados correctamente.');
  }
}