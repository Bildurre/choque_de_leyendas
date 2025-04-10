<?php

namespace Database\Seeders;

use App\Models\HeroClass;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HeroClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
      $classes = [
        [
          'name' => 'Rogue',
          'passive' => 'Esta es la pasiva del picaro',
          'hero_superclass_id' => 1,
          'agility_modifier' => 1,
          'mental_modifier' => 0,
          'will_modifier' => 0,
          'strength_modifier' => 0,
          'armor_modifier' => -1,
        ],
        [
          'name' => 'Priest',
          'passive' => 'Esta es la pasiva del sacerdote',
          'hero_superclass_id' => 2,
          'agility_modifier' => 0,
          'mental_modifier' => 1,
          'will_modifier' => 2,
          'strength_modifier' => -1,
          'armor_modifier' => -1,
        ],
      ];

      foreach ($classes as $class) {
        HeroClass::create($class);
      }

      $this->command->info('Clases iniciales creadas correctamente.');
    }
}
