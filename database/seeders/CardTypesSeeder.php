<?php

namespace Database\Seeders;

use App\Models\CardType;
use Illuminate\Database\Seeder;

class CardTypesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $types = [
      ['name' => ["en" => "Equipment", "es" => "Equipo"]],
      ['name' => ["en" => "Support", "es" => "Apoyo"]],
      ['name' => ["en" => "Trick", "es" => "Ardid"]],
      [
        'name' => ["en" => "Technique", "es" => "Técnica"],
        'hero_superclass_id' => 1
      ],
      [
        'name' => ["en" => "Spell", "es" => "Hechizo"],
        'hero_superclass_id' => 2
      ]
    ];

    foreach ($types as $type) {
      CardType::create($type);
    }

    $this->command->info('Tipos de Carta iniciales creados correctamente.');
  }
}