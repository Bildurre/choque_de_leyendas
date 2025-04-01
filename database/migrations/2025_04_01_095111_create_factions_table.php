<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('factions', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('lore_text')->nullable();
      $table->string('color', 7); // formato HEX #RRGGBB
      $table->string('icon')->nullable(); // ruta al icono o SVG
      $table->boolean('text_is_dark')->default(false); // true = texto oscuro, false = texto claro
      $table->datetimes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('factions');
  }
};