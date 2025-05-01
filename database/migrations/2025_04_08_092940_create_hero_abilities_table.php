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
    Schema::create('hero_abilities', function (Blueprint $table) {
      $table->id();
      $table->json('name');
      $table->json('description')->nullable();
      $table->foreignId('attack_range_id')->nullable()->constrained()->nullOnDelete();
      $table->foreignId('attack_subtype_id')->nullable()->constrained()->nullOnDelete();
      $table->boolean('area')->default(false);
      $table->string('cost', 5); // Formato: RGBRG, RGB, etc.
      $table->datetimes();
    });

    // Tabla pivot para la relación muchos a muchos entre héroes y habilidades
    Schema::create('hero_hero_ability', function (Blueprint $table) {
      $table->id();
      $table->foreignId('hero_id')->constrained()->onDelete('cascade');
      $table->foreignId('hero_ability_id')->constrained()->onDelete('cascade');
      $table->datetimes();

      // Índice único para evitar duplicados
      $table->unique(['hero_id', 'hero_ability_id'], 'hero_ability_unique');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hero_hero_ability');
    Schema::dropIfExists('hero_abilities');
  }
};