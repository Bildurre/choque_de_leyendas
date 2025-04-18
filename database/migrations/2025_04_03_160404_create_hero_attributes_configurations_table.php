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
    Schema::create('hero_attributes_configurations', function (Blueprint $table) {
      $table->id();
      
      // Attribute value constraints
      $table->integer('min_attribute_value')->default(1);
      $table->integer('max_attribute_value')->default(5);
      
      // Total attributes constraints
      $table->integer('min_total_attributes')->default(12);
      $table->integer('max_total_attributes')->default(18);
      
      // Health calculation multipliers
      $table->integer('agility_multiplier')->default(-1);
      $table->integer('mental_multiplier')->default(-1);
      $table->integer('will_multiplier')->default(1);
      $table->integer('strength_multiplier')->default(-1);
      $table->integer('armor_multiplier')->default(1);
      
      // Base health value
      $table->integer('total_health_base')->default(25);
      
      // Timestamps
      $table->datetimes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hero_attributes_configurations');
  }
};