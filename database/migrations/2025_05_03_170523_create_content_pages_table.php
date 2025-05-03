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
      $table->string('slug')->unique();
      $table->string('type')->default('standard'); // standard, rules, components, annexes, home, etc.
      $table->json('meta_description')->nullable();
      $table->string('background_image')->nullable(); // Imagen de fondo
      $table->json('header_config')->nullable(); // Config del header (color, overlay, etc.)
      $table->boolean('show_index')->default(false); // Mostrar índice automático
      $table->boolean('is_published')->default(false);
      $table->datetimes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('content_pages');
  }
};