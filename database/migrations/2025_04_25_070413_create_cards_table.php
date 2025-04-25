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
      $table->string('name');
      $table->string('slug')->unique();
      $table->string('image')->nullable();
      $table->foreignId('faction_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('card_type_id')->constrained()->onDelete('restrict');
      $table->foreignId('equipment_type_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('attack_range_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('attack_subtype_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('hero_ability_id')->nullable()->constrained()->onDelete('set null');
      $table->tinyInteger('hands')->nullable()->comment('1 or 2 hands for weapons');
      $table->string('cost', 5)->nullable();
      $table->text('effect')->nullable();
      $table->text('restriction')->nullable();
      $table->boolean('blast')->default(false);
      $table->datetimes();
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