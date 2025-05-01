<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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

    // Insert a default configuration record
    DB::table('hero_attributes_configurations')->insert([
      'min_attribute_value' => 1,
      'max_attribute_value' => 5,
      'min_total_attributes' => 12,
      'max_total_attributes' => 18,
      'agility_multiplier' => -1,
      'mental_multiplier' => -1,
      'will_multiplier' => 1,
      'strength_multiplier' => -1,
      'armor_multiplier' => 1,
      'total_health_base' => 30,
      'created_at' => now(),
      'updated_at' => now()
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hero_attributes_configurations');
  }
};