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
        Schema::create('hero_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->text('passive')->nullable();
            $table->foreignId('hero_superclass_id')->nullable()->constrained()->onDelete('set null');
            
            // Attribute modifiers
            $table->integer('agility_modifier')->default(0);
            $table->integer('mental_modifier')->default(0);
            $table->integer('will_modifier')->default(0);
            $table->integer('strength_modifier')->default(0);
            $table->integer('armor_modifier')->default(0);
            
            $table->datetimes();
        });
    }

    /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('hero_classes', function (Blueprint $table) {
      $table->dropForeign(['superclass_id']);
      $table->dropColumn('superclass_id');
      
      // Si queremos volver a la versión anterior
      $table->string('superclass')->nullable();
    });
  }
};
