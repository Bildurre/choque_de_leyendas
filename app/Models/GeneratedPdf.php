<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GeneratedPdf extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'type',
        'template',
        'filename',
        'path',
        'session_id',
        'locale',
        'faction_id',
        'deck_id',
        'is_permanent',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_permanent' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the faction associated with this PDF
     */
    public function faction(): BelongsTo
    {
        return $this->belongsTo(Faction::class);
    }

    /**
     * Get the deck associated with this PDF
     */
    public function deck(): BelongsTo
    {
        return $this->belongsTo(FactionDeck::class, 'deck_id');
    }

    /**
     * Check if the PDF file exists in storage
     */
    public function exists(): bool
    {
        return Storage::disk('public')->exists($this->path);
    }

    /**
     * Get the URL for the PDF
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    /**
     * Get the size of the PDF file
     */
    public function getSizeAttribute(): int
    {
        if (!$this->exists()) {
            return 0;
        }
        
        return Storage::disk('public')->size($this->path);
    }

    /**
     * Get the formatted size of the PDF file
     */
    public function getFormattedSizeAttribute(): string
    {
        $size = $this->size;
        
        if ($size >= 1048576) {
            return round($size / 1048576, 2) . ' MB';
        } elseif ($size >= 1024) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return $size . ' bytes';
        }
    }

    /**
     * Scope for permanent PDFs
     */
    public function scopePermanent($query)
    {
        return $query->where('is_permanent', true);
    }

    /**
     * Scope for temporary PDFs
     */
    public function scopeTemporary($query)
    {
        return $query->where('is_permanent', false);
    }

    /**
     * Scope for PDFs of a specific session
     */
    public function scopeForSession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope for expired PDFs
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    /**
     * Delete the model and its associated file
     */
    public function delete()
    {
        // Delete the file if it exists
        if ($this->exists()) {
            Storage::disk('public')->delete($this->path);
        }

        return parent::delete();
    }
}