<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('passive')->nullable();
            
            // Attribute modifiers
            $table->integer('agility_modifier')->default(0);
            $table->integer('mental_modifier')->default(0);
            $table->integer('will_modifier')->default(0);
            $table->integer('strength_modifier')->default(0);
            $table->integer('armor_modifier')->default(0);
            
            $table->datetimes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_classes');
    }
};
