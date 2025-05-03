<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('content_blocks', function (Blueprint $table) {
      $table->id();
      $table->foreignId('content_section_id')->constrained()->onDelete('cascade');
      $table->string('type'); // text, image, text_image, list, table, model_list, cta, etc.
      $table->json('content')->nullable(); // Contenido principal
      $table->string('image')->nullable(); // Imagen principal
      $table->json('metadata')->nullable(); // Estructura para títulos, subtítulos, anclas, etc.
      $table->string('model_type')->nullable(); // Para listas de modelos (heroes, cards, factions)
      $table->json('filters')->nullable(); // Filtros para listas de modelos
      $table->json('settings')->nullable(); // Configuración específica del bloque
      $table->integer('order')->default(0);
      $table->boolean('include_in_index')->default(false);
      $table->datetimes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('content_blocks');
  }
};