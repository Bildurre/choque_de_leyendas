<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroAttributeConfiguration;

class HeroAttributeConfigurationSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Ensure only one configuration exists
    HeroAttributeConfiguration::updateOrCreate(
      ['id' => 1], 
      [
        'base_agility' => 3,
        'base_mental' => 3,
        'base_will' => 3,
        'base_strength' => 3,
        'base_armor' => 3,
        'total_points' => 45
      ]
    );

    $this->command->info('Hero Attribute Configuration seeded successfully.');
  }
}