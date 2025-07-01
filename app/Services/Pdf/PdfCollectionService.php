<?php

namespace App\Services\Pdf;

use App\Models\GeneratedPdf;
use App\Models\Faction;
use App\Models\FactionDeck;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PdfCollectionService
{
  /**
   * Get all public PDFs organized by type for the given locale
   */
  public function getPublicPdfs(string $locale): array
  {
    return [
      'factions' => $this->getFactionPdfs($locale),
      'decks' => $this->getDeckPdfs($locale),
      'others' => $this->getOtherPdfs($locale),
      'temporary' => $this->getTemporaryPdfs(),
    ];
  }
  
  /**
   * Check if a PDF can be deleted by the current session
   */
  public function canDelete(GeneratedPdf $pdf, string $sessionId): bool
  {
    return !$pdf->is_permanent && $pdf->session_id === $sessionId;
  }
  
  /**
   * Delete a PDF file and its record
   */
  public function deletePdf(GeneratedPdf $pdf): bool
  {
    try {
      // Delete file from storage
      if (Storage::disk('public')->exists($pdf->path)) {
        Storage::disk('public')->delete($pdf->path);
      }
      
      // Delete database record
      $pdf->delete();
      
      return true;
    } catch (\Exception $e) {
      \Log::error('Failed to delete PDF in service', [
        'pdf_id' => $pdf->id,
        'error' => $e->getMessage(),
      ]);
      
      return false;
    }
  }
  
  /**
   * Get faction PDFs for the current locale
   */
  private function getFactionPdfs(string $locale): Collection
  {
    // Get published faction IDs
    $publishedFactionIds = Faction::published()->pluck('id')->toArray();
    
    return GeneratedPdf::where('type', 'faction')
      ->where('is_permanent', true)
      ->where('locale', $locale)
      ->whereIn('faction_id', $publishedFactionIds)
      ->with('faction')
      ->orderBy('created_at', 'desc')
      ->get()
      ->map(function ($pdf) {
        // Add faction name to PDF for display
        $pdf->display_name = $pdf->faction ? $pdf->faction->name : $pdf->filename;
        return $pdf;
      });
  }
  
  /**
   * Get deck PDFs for the current locale
   */
  private function getDeckPdfs(string $locale): Collection
  {
    // Get published deck IDs
    $publishedDeckIds = FactionDeck::published()->pluck('id')->toArray();
    
    return GeneratedPdf::where('type', 'deck')
      ->where('is_permanent', true)
      ->where('locale', $locale)
      ->whereIn('deck_id', $publishedDeckIds)
      ->with(['deck.faction'])
      ->orderBy('created_at', 'desc')
      ->get()
      ->map(function ($pdf) {
        // Add deck name to PDF for display
        if ($pdf->deck) {
          $pdf->display_name = $pdf->deck->name . ' (' . $pdf->deck->faction->name . ')';
        } else {
          $pdf->display_name = $pdf->filename;
        }
        return $pdf;
      });
  }
  
  /**
   * Get other PDFs (rules, tokens) for the current locale
   */
  private function getOtherPdfs(string $locale): Collection
  {
    return GeneratedPdf::whereIn('type', ['rules', 'tokens'])
      ->where('is_permanent', true)
      ->where(function ($query) use ($locale) {
        $query->where('locale', $locale)
              ->orWhereNull('locale');
      })
      ->orderBy('type')
      ->orderBy('created_at', 'desc')
      ->get()
      ->map(function ($pdf) {
        // Add display name based on type
        $pdf->display_name = __('admin.pdf_export.' . $pdf->type);
        return $pdf;
      });
  }
  
  /**
   * Get temporary PDFs for the current session
   */
  private function getTemporaryPdfs(): Collection
  {
    $sessionId = session()->getId();
    
    return GeneratedPdf::where('session_id', $sessionId)
      ->where('is_permanent', false)
      ->where('expires_at', '>', now())
      ->orderBy('created_at', 'desc')
      ->get();
  }
}