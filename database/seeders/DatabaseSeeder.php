<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // User::factory(10)->create();

    // User::factory()->create([
    //     'name' => 'Test User',
    //     'email' => 'test@example.com',
    // ]);
    $this->call([
      AdminUserSeeder::class,
      FactionsSeeder::class,
      HeroSuperclassesSeeder::class,
      HeroClassesSeeder::class,
      AttackSubtypesSeeder::class,
      AttackRangesSeeder::class,
      HeroAbilitiesSeeder::class,
      HeroRacesSeeder::class,
      CardTypesSeeder::class,
      EquipmentTypesSeeder::class,
      HeroesSeeder::class,
      HeroHeroAbilitiesSeeder::class,
      CardsSeeder::class,
      CountersSeeder::class,
      PagesWithBlocksSeeder::class,
    ]);
  }
}
