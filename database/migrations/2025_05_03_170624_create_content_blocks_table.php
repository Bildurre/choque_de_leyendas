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
      $table->string('type');
      $table->json('content')->nullable();
      $table->string('image')->nullable();
      $table->enum('image_position', ['left', 'right', 'none'])->default('none');
      $table->integer('order')->default(0);
      $table->boolean('include_in_index')->default(false);
      $table->string('model_type')->nullable();
      $table->json('model_filters')->nullable();
      $table->json('style_settings')->nullable();
      $table->datetimes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('content_blocks');
  }
};