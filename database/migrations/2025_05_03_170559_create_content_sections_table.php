<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('content_sections', function (Blueprint $table) {
      $table->id();
      $table->foreignId('content_page_id')->constrained()->onDelete('cascade');
      $table->json('title');
      $table->string('slug')->nullable();
      $table->string('anchor_id')->nullable(); // For linking to specific sections
      $table->integer('order')->default(0);
      $table->boolean('include_in_index')->default(true);
      $table->datetimes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('content_sections');
  }
};