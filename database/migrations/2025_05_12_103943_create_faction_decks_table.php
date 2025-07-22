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
    Schema::create('faction_decks', function (Blueprint $table) {
      $table->id();
      $table->foreignId('game_mode_id')->nullable()->constrained('game_modes');
      $table->json('name');
      $table->json('description')->nullable();
      $table->json('epic_quote')->nullable();
      $table->json('slug');
      $table->text('icon')->nullable();
      $table->foreignId('faction_id')->constrained('factions');
      $table->boolean('is_published')->default(false);
      $table->datetimes();
      $table->softDeletes();
    });

    // Pivot table for cards
    Schema::create('card_faction_deck', function (Blueprint $table) {
      $table->id();
      $table->foreignId('card_id')->constrained('cards');
      $table->foreignId('faction_deck_id')->constrained('faction_decks');
      $table->integer('copies')->default(1);
      $table->datetimes();
    });

    // Pivot table for heroes
    Schema::create('faction_deck_hero', function (Blueprint $table) {
      $table->id();
      $table->foreignId('hero_id')->constrained('heroes');
      $table->foreignId('faction_deck_id')->constrained('faction_decks');
      $table->integer('copies')->default(1);
      $table->datetimes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('faction_deck_hero');
    Schema::dropIfExists('card_faction_deck');
    Schema::dropIfExists('faction_decks');
  }
};