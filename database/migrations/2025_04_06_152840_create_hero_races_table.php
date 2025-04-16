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
    Schema::create('hero_races', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->integer('agility_modifier')->default(0);
      $table->integer('mental_modifier')->default(0);
      $table->integer('will_modifier')->default(0);
      $table->integer('strength_modifier')->default(0);
      $table->integer('armor_modifier')->default(0);
      $table->datetimes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hero_races');
  }
};