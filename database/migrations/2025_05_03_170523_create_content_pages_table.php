<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('content_pages', function (Blueprint $table) {
      $table->id();
      $table->json('title');
      $table->json('slug');
      $table->boolean('is_published')->default(false);
      $table->json('meta_description')->nullable();
      $table->string('background_image')->nullable();
      $table->integer('order')->default(0);
      $table->boolean('show_in_menu')->default(true);
      $table->string('parent_slug')->nullable();
      
      $table->datetimes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('content_pages');
  }
};