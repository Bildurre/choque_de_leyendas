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
    Schema::create('attack_subtypes', function (Blueprint $table) {
      $table->id();
      $table->string('name')->unique();
      $table->foreignId('attack_type_id')->constrained()->onDelete('cascade');
      $table->datetimes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('attack_subtypes');
  }
};