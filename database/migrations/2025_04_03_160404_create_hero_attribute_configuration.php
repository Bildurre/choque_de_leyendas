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
    Schema::create('hero_attribute_configurations', function (Blueprint $table) {
      $table->id();
      
      // Base values for each attribute
      $table->integer('base_agility')->default(3);
      $table->integer('base_mental')->default(3);
      $table->integer('base_will')->default(3);
      $table->integer('base_strength')->default(3);
      $table->integer('base_armor')->default(3);
      
      // Total maximum points for hero creation
      $table->integer('total_points')->default(45);
      
      
      // Timestamps
      $table->datetimes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hero_attribute_configurations');
  }
};