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
    Schema::create('card_types', function (Blueprint $table) {
      $table->id();
      $table->json('name');
      $table->foreignId('hero_superclass_id')->nullable()->unique()->constrained()->nullOnDelete();
      $table->datetimes();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('card_types');
  }
};