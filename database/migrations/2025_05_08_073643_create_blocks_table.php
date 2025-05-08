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
    Schema::create('blocks', function (Blueprint $table) {
      $table->id();
      $table->foreignId('page_id')->constrained()->onDelete('cascade');
      $table->string('type');
      $table->json('title')->nullable();
      $table->json('subtitle')->nullable();
      $table->json('content')->nullable();
      $table->integer('order')->default(0);
      $table->string('background_color')->nullable();
      $table->string('background_image')->nullable();
      $table->json('settings')->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('blocks');
  }
};