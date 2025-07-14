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
            $table->string('filename');
            $table->string('path');
            $table->string('session_id')->nullable(); // for temporary PDFs
            $table->string('locale', 5)->nullable();
            $table->unsignedBigInteger('faction_id')->nullable();
            $table->unsignedBigInteger('deck_id')->nullable();
            $table->unsignedBigInteger('page_id')->nullable();
            $table->boolean('is_permanent')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('type');
            $table->index('session_id');
            $table->index('is_permanent');
            $table->index('expires_at');
            $table->index('locale');
            $table->index('faction_id');
            $table->index('deck_id');
            $table->index('page_id');
            $table->index(['type', 'locale']);
            $table->index(['faction_id', 'locale']);
            $table->index(['deck_id', 'locale']);
            $table->index(['page_id', 'locale']);
            $table->index(['type', 'is_permanent', 'locale']);
            
            // Foreign keys
            $table->foreign('faction_id')->references('id')->on('factions')->onDelete('cascade');
            $table->foreign('deck_id')->references('id')->on('faction_decks')->onDelete('cascade');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
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