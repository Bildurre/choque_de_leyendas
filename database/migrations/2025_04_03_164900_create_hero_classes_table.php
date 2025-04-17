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
    Schema::create('hero_classes', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->text('passive')->nullable();
      $table->foreignId('hero_superclass_id')->nullable()->constrained()->onDelete('set null');
      
      $table->datetimes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('hero_classes');
  }
};
