<?php

namespace Database\Seeders;

use App\Models\AttackRange;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AttackRangeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Leer el archivo JSON
    $json = File::get(database_path('data/attack-ranges.json'));
    $ranges = json_decode($json, true);

    // Crear la carpeta para los iconos de facciones si no existe
    if (!File::exists(storage_path('app/public/attack-ranges-icons'))) {
      File::makeDirectory(storage_path('app/public/attack-ranges-icons'), 0755, true);
    }

    // Insertar cada facción
    foreach ($ranges as $rangeData) {
      $range = new AttackRange();
      $range->name = $rangeData['nombre'];
      $range->description = $rangeData['descripcion'];
      
      $range->save();
      
      $this->command->info("Rango '{$range->name}' creado con éxito.");
    }
  }
}