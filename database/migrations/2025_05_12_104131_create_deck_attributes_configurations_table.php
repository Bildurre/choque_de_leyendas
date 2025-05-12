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
    Schema::create('deck_attributes_configurations', function (Blueprint $table) {
      $table->id();
      $table->foreignId('game_mode_id')->nullable()->constrained('game_modes');
      $table->integer('min_cards')->default(30);
      $table->integer('max_cards')->default(40);
      $table->integer('max_copies_per_card')->default(2);
      $table->integer('max_copies_per_hero')->default(1);
      $table->datetimes();
    });

    DB::table('deck_attributes_configurations')->insert([
      'game_mode_id' => 1,
      'min_cards' => 30,
      'max_cards' => 40,
      'max_copies_per_card' => 2,
      'max_copies_per_hero' => 1,
      'created_at' => now(),
      'updated_at' => now()
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('deck_attributes_configurations');
  }
};