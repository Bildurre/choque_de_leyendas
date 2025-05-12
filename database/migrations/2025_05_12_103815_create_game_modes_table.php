<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('game_modes', function (Blueprint $table) {
      $table->id();
      $table->json('name');
      $table->json('description')->nullable();
      $table->datetimes();
      $table->softDeletes();
    });

    DB::table('game_modes')->insert([
      'name' => json_encode(['es' => 'Estándar', 'en' => 'Standard']),
      'description' => json_encode(['es' => 'Modo de juego estándar', 'en' => 'Standard game mode']),
      'created_at' => now(),
      'updated_at' => now()
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('game_modes');
  }
};