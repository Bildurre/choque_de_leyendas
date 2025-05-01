<?php

namespace Database\Seeders;

use App\Models\EquipmentType;
use Illuminate\Database\Seeder;

class EquipmentTypesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $types = [
      [
        'name' => ["en" => "Sword", "es" => "Espada"],
        'category' => 'weapon'
      ],
      [
        'name' => ["en" => "Axe", "es" => "Hacha"],
        'category' => 'weapon'
      ],
      [
        'name' => ["en" => "Mace", "es" => "Maza"],
        'category' => 'weapon'
      ],
      [
        'name' => ["en" => "Dagger", "es" => "Daga"],
        'category' => 'weapon'
      ],
      [
        'name' => ["en" => "Spear", "es" => "Lanza"],
        'category' => 'weapon'
      ],
      [
        'name' => ["en" => "Shield", "es" => "Escudo"],
        'category' => 'weapon'
      ],
      [
        'name' => ["en" => "Banner", "es" => "Estandarte"],
        'category' => 'weapon'
      ],
      [
        'name' => ["en" => "Bow", "es" => "Arco"],
        'category' => 'weapon'
      ],
      [
        'name' => ["en" => "Scepter", "es" => "Cetro"],
        'category' => 'weapon'
      ],
      [
        'name' => ["en" => "Staff", "es" => "Báculo"],
        'category' => 'weapon'
      ],
      [
        'name' => ["en" => "Greaves", "es" => "Grevas"],
        'category' => 'armor'
      ],
      [
        'name' => ["en" => "Chestplate", "es" => "Peto"],
        'category' => 'armor'
      ],
      [
        'name' => ["en" => "Gauntlets", "es" => "Guanteletes"],
        'category' => 'armor'
      ],
      [
        'name' => ["en" => "Helm", "es" => "Yelmo"],
        'category' => 'armor'
      ],
      [
        'name' => ["en" => "Amulet", "es" => "Amuleto"],
        'category' => 'armor'
      ],
      [
        'name' => ["en" => "Cloak", "es" => "Capa"],
        'category' => 'armor'
      ],
    ];


    foreach ($types as $type) {
      EquipmentType::create($type);
    }

    $this->command->info('Tipos de Equipo iniciales creados correctamente.');
  }
}