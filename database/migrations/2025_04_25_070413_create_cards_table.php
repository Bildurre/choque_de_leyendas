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
    Schema::create('cards', function (Blueprint $table) {
      $table->id();
      $table->json('name'); // Cambiado a json para traducciones
      $table->json('slug');
      $table->string('image')->nullable();
      $table->json('lore_text')->nullable(); // Cambiado a json
      $table->foreignId('faction_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('card_type_id')->constrained()->onDelete('restrict');
      $table->foreignId('equipment_type_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('attack_range_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('attack_subtype_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('hero_ability_id')->nullable()->constrained()->onDelete('set null');
      $table->tinyInteger('hands')->nullable()->comment('1 or 2 hands for weapons');
      $table->string('cost', 5)->nullable();
      $table->json('effect')->nullable(); // Cambiado a json
      $table->json('restriction')->nullable(); // Cambiado a json
      $table->boolean('area')->default(false);
      $table->boolean('is_published')->default(false);
      $table->datetimes();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('cards');
  }
};