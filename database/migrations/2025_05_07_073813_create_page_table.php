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
    Schema::create('pages', function (Blueprint $table) {
      $table->id();
      $table->json('slug');
      $table->json('title');
      $table->json('description')->nullable();
      $table->string('image')->nullable();
      $table->string('background_image')->nullable();
      $table->boolean('is_published')->default(false);
      $table->json('meta_title')->nullable();
      $table->json('meta_description')->nullable();
      $table->foreignId('parent_id')->nullable()->references('id')->on('pages')->nullOnDelete();
      $table->string('template')->default('default');
      $table->integer('order')->default(0);
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('pages');
  }
};