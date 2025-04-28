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
      $table->json('name');
      $table->string('slug')->unique();
      $table->json('lore_text')->nullable();
      $table->string('color', 7);
      $table->string('icon')->nullable();
      $table->boolean('text_is_dark')->default(false);
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