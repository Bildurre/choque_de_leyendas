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
    Schema::create('heroes', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('slug')->unique();
      $table->text('lore_text')->nullable();
      $table->string('passive_name')->nullable();
      $table->text('passive_description')->nullable();
      $table->string('image')->nullable();
      $table->foreignId('faction_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('hero_race_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('hero_class_id')->nullable()->constrained()->onDelete('set null');
      $table->enum('gender', ['male', 'female'])->default('male');
      $table->integer('agility')->default(2);
      $table->integer('mental')->default(2);
      $table->integer('will')->default(2);
      $table->integer('strength')->default(2);
      $table->integer('armor')->default(2);
      $table->datetimes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('heroes');
  }
};