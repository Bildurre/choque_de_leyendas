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
    Schema::create('attack_types', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->text('description')->nullable();
      $table->string('color', 7)->default('#3d3df5');
      $table->boolean('text_is_dark')->default(false);
      $table->datetimes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('attack_types');
  }
};