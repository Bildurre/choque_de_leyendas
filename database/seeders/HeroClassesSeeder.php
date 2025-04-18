<?php

namespace Database\Seeders;

use App\Models\HeroClass;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class HeroClassesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
      
    // Leer el archivo JSON
    $json = File::get(database_path('data/heroClasses.json'));
    $classes = json_decode($json, true);

    // Insertar cada facción
    foreach ($classes as $classData) {
      $class = new HeroClass();
      $class->name = $classData['name'];
      $class->passive = $classData['passive'];
      $class->hero_superclass_id = $classData['hero_superclass_id'];

      $class->save();
    }
    $this->command->info("Clases iniciales creadas con exito");
  }
}