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
    Schema::create('generated_pdfs', function (Blueprint $table) {
      $table->id();
      $table->string('type'); // collection, faction, deck, rules, tokens, etc.
      $table->string('template'); // collection, rules, tokens, etc.
      $table->string('filename');
      $table->string('path');
      $table->string('session_id')->nullable(); // for temporary PDFs
      $table->json('metadata')->nullable(); // options, entities included, etc.
      $table->boolean('is_permanent')->default(false);
      $table->timestamp('expires_at')->nullable();
      $table->timestamps();
      
      // Indexes
      $table->index('type');
      $table->index('session_id');
      $table->index('is_permanent');
      $table->index('expires_at');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('generated_pdfs');
  }
};