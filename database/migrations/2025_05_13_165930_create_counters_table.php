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
    Schema::create('counters', function (Blueprint $table) {
      $table->id();
      $table->json('name');
      $table->json('effect')->nullable();
      $table->enum('type', ['boon', 'bane']);
      $table->boolean('is_published')->default(false);
      $table->string('icon')->nullable();
      $table->datetimes();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('counters');
  }
};