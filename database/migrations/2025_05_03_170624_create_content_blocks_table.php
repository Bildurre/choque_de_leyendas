<?php
// create_content_blocks_table.php
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
      $table->string('type'); // text, image, text_image, list, table, model_list, etc.
      $table->json('content')->nullable();
      $table->string('image')->nullable();
      $table->string('model_type')->nullable(); // For model_list type (heroes, cards, factions)
      $table->json('filters')->nullable(); // Filters for model lists
      $table->json('settings')->nullable(); // For styling, layout, etc.
      $table->integer('order')->default(0);
      $table->datetimes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('content_blocks');
  }
};