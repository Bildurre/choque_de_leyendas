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
      $table->text('description')->nullable();
      $table->foreignId('faction_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('hero_race_id')->nullable()->constrained()->onDelete('set null');
      $table->foreignId('hero_class_id')->nullable()->constrained()->onDelete('set null');
      $table->integer('agility')->default(0);
      $table->integer('mental')->default(0);
      $table->integer('will')->default(0);
      $table->integer('strength')->default(0);
      $table->integer('armor')->default(0);
      $table->integer('health')->default(10);
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