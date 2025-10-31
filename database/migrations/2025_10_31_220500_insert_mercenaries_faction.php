<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    // CRITICAL: This migration reorders all faction IDs to make room for Mercenaries at ID=1
    // All existing factions will be shifted: 1→2, 2→3, 3→4
    
    // Step 1: Disable foreign key checks temporarily
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
    // Step 2: Update factions in REVERSE order to avoid ID conflicts
    // Get all factions ordered by ID DESC
    $factions = DB::table('factions')->orderBy('id', 'desc')->get();
    
    foreach ($factions as $faction) {
      $newId = $faction->id + 1;
      DB::table('factions')->where('id', $faction->id)->update(['id' => $newId]);
    }
    
    // Step 3: Update heroes faction_id references
    $heroes = DB::table('heroes')->whereNotNull('faction_id')->orderBy('faction_id', 'desc')->get();
    
    foreach ($heroes as $hero) {
      $newFactionId = $hero->faction_id + 1;
      DB::table('heroes')->where('id', $hero->id)->update(['faction_id' => $newFactionId]);
    }
    
    // Step 4: Update cards faction_id references
    $cards = DB::table('cards')->whereNotNull('faction_id')->orderBy('faction_id', 'desc')->get();
    
    foreach ($cards as $card) {
      $newFactionId = $card->faction_id + 1;
      DB::table('cards')->where('id', $card->id)->update(['faction_id' => $newFactionId]);
    }
    
    // Step 5: Update faction_decks faction_id references (if any exist)
    if (Schema::hasTable('faction_decks')) {
      $decks = DB::table('faction_decks')->orderBy('faction_id', 'desc')->get();
      
      foreach ($decks as $deck) {
        $newFactionId = $deck->faction_id + 1;
        DB::table('faction_decks')->where('id', $deck->id)->update(['faction_id' => $newFactionId]);
      }
    }
    
    // Step 6: Reset auto-increment to allow ID=1
    DB::statement('ALTER TABLE factions AUTO_INCREMENT = 1');
    
    // Step 7: Insert Mercenaries faction at ID=1
    DB::table('factions')->insert([
      'id' => 1,
      'name' => json_encode([
        'en' => 'Mercenaries',
        'es' => 'Mercenarios'
      ]),
      'slug' => json_encode([
        'en' => 'mercenaries',
        'es' => 'mercenarios'
      ]),
      'lore_text' => json_encode([
        'en' => 'The Mercenaries have no flag, no homeland, no loyalty beyond gold. They are warriors from all corners of Ulkhimarel, united only by their willingness to fight for the highest bidder. In battle, they are unpredictable and diverse, bringing skills from every faction. Where others see betrayal, they see opportunity.',
        'es' => 'Los Mercenarios no tienen bandera, ni patria, ni lealtad más allá del oro. Son guerreros de todos los rincones de Ulkhimarel, unidos solo por su disposición a luchar por el mejor postor. En batalla, son impredecibles y diversos, trayendo habilidades de cada facción. Donde otros ven traición, ellos ven oportunidad.'
      ]),
      'epic_quote' => json_encode([
        'en' => 'Loyalty is golden, and gold is our only master.',
        'es' => 'La lealtad es dorada, y el oro es nuestro único amo.'
      ]),
      'color' => '#9E9E9E', // Gray color for mercenaries
      'icon' => 'images/factions/mercenarios.jpeg', // Placeholder icon
      'text_is_dark' => false,
      'is_published' => true,
      'created_at' => now(),
      'updated_at' => now(),
    ]);
    
    // Step 8: Re-enable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Disable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    
    // Delete Mercenaries faction
    DB::table('factions')->where('id', 1)->delete();
    
    // Shift all factions back down: 2→1, 3→2, 4→3
    $factions = DB::table('factions')->orderBy('id', 'asc')->get();
    
    foreach ($factions as $faction) {
      $newId = $faction->id - 1;
      DB::table('factions')->where('id', $faction->id)->update(['id' => $newId]);
    }
    
    // Update heroes faction_id references back
    $heroes = DB::table('heroes')->whereNotNull('faction_id')->orderBy('faction_id', 'asc')->get();
    
    foreach ($heroes as $hero) {
      $newFactionId = $hero->faction_id - 1;
      DB::table('heroes')->where('id', $hero->id)->update(['faction_id' => $newFactionId]);
    }
    
    // Update cards faction_id references back
    $cards = DB::table('cards')->whereNotNull('faction_id')->orderBy('faction_id', 'asc')->get();
    
    foreach ($cards as $card) {
      $newFactionId = $card->faction_id - 1;
      DB::table('cards')->where('id', $card->id)->update(['faction_id' => $newFactionId]);
    }
    
    // Update faction_decks faction_id references back (if any exist)
    if (Schema::hasTable('faction_decks')) {
      $decks = DB::table('faction_decks')->orderBy('faction_id', 'asc')->get();
      
      foreach ($decks as $deck) {
        $newFactionId = $deck->faction_id - 1;
        DB::table('faction_decks')->where('id', $deck->id)->update(['faction_id' => $newFactionId]);
      }
    }
    
    // Re-enable foreign key checks
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
  }
};