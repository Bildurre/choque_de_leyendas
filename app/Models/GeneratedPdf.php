<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
    'filename',
    'path',
    'session_id',
    'metadata',
    'is_permanent',
    'expires_at',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'metadata' => 'array',
    'is_permanent' => 'boolean',
    'expires_at' => 'datetime',
  ];

  /**
   * The "booted" method of the model.
   */
  protected static function booted(): void
  {
    // Delete file when model is deleted
    static::deleting(function ($pdf) {
      if (Storage::disk('public')->exists($pdf->path)) {
        Storage::disk('public')->delete($pdf->path);
      }
    });
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
   * Scope for session PDFs
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
   * Get the full URL to the PDF
   */
  public function getUrlAttribute(): string
  {
    return Storage::disk('public')->url($this->path);
  }

  /**
   * Get the file size in bytes
   */
  public function getSizeAttribute(): int
  {
    return Storage::disk('public')->size($this->path);
  }

  /**
   * Get the file size formatted for humans
   */
  public function getFormattedSizeAttribute(): string
  {
    $size = $this->size;
    $units = ['B', 'KB', 'MB', 'GB'];
    $power = floor(($size ? log($size) : 0) / log(1024));
    $power = min($power, count($units) - 1);
    
    return round($size / pow(1024, $power), 2) . ' ' . $units[$power];
  }

  /**
   * Check if the PDF still exists
   */
  public function exists(): bool
  {
    return Storage::disk('public')->exists($this->path);
  }

  /**
   * Mark as permanent
   */
  public function makePermanent(): void
  {
    $this->update([
      'is_permanent' => true,
      'expires_at' => null,
      'session_id' => null,
    ]);
  }

  /**
   * Set expiration
   */
  public function setExpiration($hours = 24): void
  {
    $this->update([
      'expires_at' => now()->addHours($hours),
    ]);
  }
}