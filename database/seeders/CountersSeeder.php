<?php

namespace Database\Seeders;

use App\Models\Counter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountersSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Leer el archivo JSON
    $json = File::get(database_path('data/counters.json'));
    $counters = json_decode($json, true);

    // Insertar cada facción
    foreach ($counters as $data) {
      $counter = new Counter();
      $counter->name = $data['name'];
      $counter->effect = $data['effect'];
      $counter->type = $data['type'];
      $counter->icon = $data['icon'];
      $counter->is_published = true;
            
      $counter->save();
    }

    $this->command->info("Contadores iniciales creadas con éxito.");
  }
}