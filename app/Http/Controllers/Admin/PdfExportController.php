<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faction;
use App\Models\FactionDeck;
use App\Models\GeneratedPdf;
use App\Services\PdfExport\PdfExportService;
use App\Services\PdfExport\Storage\PdfStorageService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class PdfExportController extends Controller
{
  public function __construct(
    private PdfExportService $pdfExportService,
    private PdfStorageService $pdfStorageService
  ) {}
  
  /**
   * Display the PDF export dashboard with tabs
   */
  public function index(Request $request): View
  {
    $activeTab = $request->get('tab', 'factions');
    
    $factions = Faction::with(['heroes', 'cards'])
      ->orderBy('name')
      ->get();
      
    $decks = FactionDeck::with(['faction', 'heroes', 'cards'])
      ->orderBy('name')
      ->get();
    
    $customExports = [
      [
        'key' => 'rules',
        'name' => __('admin.pdf_export.rules'),
        'description' => __('admin.pdf_export.rules_description'),
        'template' => 'rules',
      ],
      [
        'key' => 'tokens',
        'name' => __('admin.pdf_export.tokens'),
        'description' => __('admin.pdf_export.tokens_description'),
        'template' => 'tokens',
      ],
    ];
    
    // Check existing PDFs
    $existingPdfs = $this->getExistingPdfs();
    
    return view('admin.pdf-export.index', compact(
      'factions',
      'decks',
      'customExports',
      'existingPdfs',
      'activeTab'
    ));
  }
  
  /**
   * Get existing PDFs organized by type
   */
  private function getExistingPdfs(): array
  {
    $existingPdfs = [
      'faction' => [],
      'deck' => [],
      'custom' => [],
    ];
    
    $pdfs = GeneratedPdf::permanent()->get();
    
    foreach ($pdfs as $pdf) {
      if ($pdf->type === 'faction' && isset($pdf->metadata['faction_id'])) {
        $existingPdfs['faction'][$pdf->metadata['faction_id']] = $pdf;
      } elseif ($pdf->type === 'deck' && isset($pdf->metadata['deck_id'])) {
        $existingPdfs['deck'][$pdf->metadata['deck_id']] = $pdf;
      } elseif (in_array($pdf->type, ['rules', 'tokens'])) {
        $existingPdfs['custom'][$pdf->type] = $pdf;
      }
    }
    
    return $existingPdfs;
  }
  
  /**
   * Generate PDF for a single faction
   */
  public function generateFaction(Faction $faction): RedirectResponse
  {
    try {
      // Delete existing PDF if any
      GeneratedPdf::where('type', 'faction')
        ->where('metadata->faction_id', $faction->id)
        ->permanent()
        ->delete();
      
      $this->pdfExportService->generateAsync(
        'faction',
        'faction',
        [
          'faction_id' => $faction->id,
          'is_permanent' => true,
          'filename' => Str::slug($faction->name) . '.pdf',
          'metadata' => [
            'faction_id' => $faction->id,
            'faction_name' => $faction->name,
          ],
        ],
        null,
        true
      );
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'factions'])
        ->with('success', __('admin.pdf_generation_started'));
      
    } catch (\Exception $e) {
      \Log::error('Failed to start faction PDF generation', [
        'faction_id' => $faction->id,
        'error' => $e->getMessage(),
      ]);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'factions'])
        ->with('error', __('admin.pdf_generation_failed'));
    }
  }
  
  /**
   * Generate PDF for a single deck
   */
  public function generateDeck(FactionDeck $deck): RedirectResponse
  {
    try {
      // Delete existing PDF if any
      GeneratedPdf::where('type', 'deck')
        ->where('metadata->deck_id', $deck->id)
        ->permanent()
        ->delete();
      
      $this->pdfExportService->generateAsync(
        'deck',
        'deck',
        [
          'deck_id' => $deck->id,
          'is_permanent' => true,
          'filename' => Str::slug($deck->name) . '.pdf',
          'metadata' => [
            'deck_id' => $deck->id,
            'deck_name' => $deck->name,
            'faction_name' => $deck->faction->name,
          ],
        ],
        null,
        true
      );
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'decks'])
        ->with('success', __('admin.pdf_generation_started'));
      
    } catch (\Exception $e) {
      \Log::error('Failed to start deck PDF generation', [
        'deck_id' => $deck->id,
        'error' => $e->getMessage(),
      ]);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'decks'])
        ->with('error', __('admin.pdf_generation_failed'));
    }
  }
  
  /**
   * Generate custom PDF (rules, tokens, etc.)
   */
  public function generateCustom(Request $request): RedirectResponse
  {
    $validated = $request->validate([
      'template' => 'required|string|in:rules,tokens',
    ]);
    
    try {
      // Delete existing PDF if any
      GeneratedPdf::where('type', $validated['template'])
        ->permanent()
        ->delete();
      
      $this->pdfExportService->generateAsync(
        $validated['template'],
        $validated['template'],
        [
          'is_permanent' => true,
          'filename' => $validated['template'] . '-' . date('Y-m-d') . '.pdf',
        ],
        null,
        true
      );
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'other'])
        ->with('success', __('admin.pdf_generation_started'));
      
    } catch (\Exception $e) {
      \Log::error('Failed to start custom PDF generation', [
        'template' => $validated['template'],
        'error' => $e->getMessage(),
      ]);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => 'other'])
        ->with('error', __('admin.pdf_generation_failed'));
    }
  }
  
  /**
   * Delete a PDF
   */
  public function destroy(GeneratedPdf $pdf): RedirectResponse
  {
    $tab = 'factions'; // Default tab
    
    // Determine which tab to redirect to
    if ($pdf->type === 'deck') {
      $tab = 'decks';
    } elseif (in_array($pdf->type, ['rules', 'tokens'])) {
      $tab = 'other';
    }
    
    try {
      $pdf->delete();
      
      return redirect()->route('admin.pdf-export.index', ['tab' => $tab])
        ->with('success', __('admin.pdf_deleted_successfully'));
    } catch (\Exception $e) {
      \Log::error('Failed to delete PDF', [
        'pdf_id' => $pdf->id,
        'error' => $e->getMessage(),
      ]);
      
      return redirect()->route('admin.pdf-export.index', ['tab' => $tab])
        ->with('error', __('admin.pdf_deletion_failed'));
    }
  }
  
  /**
   * Run cleanup of temporary PDFs
   */
  public function cleanup(): RedirectResponse
  {
    try {
      $deleted = $this->pdfStorageService->cleanupExpired();
      
      return redirect()->route('admin.pdf-export.index')
        ->with('success', __('admin.pdf_cleanup_completed', ['count' => $deleted]));
    } catch (\Exception $e) {
      \Log::error('PDF cleanup failed', [
        'error' => $e->getMessage()
      ]);
      
      return redirect()->route('admin.pdf-export.index')
        ->with('error', __('admin.pdf_cleanup_failed'));
    }
  }
}