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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PdfExportController extends Controller
{
  public function __construct(
    private PdfExportService $pdfExportService,
    private PdfStorageService $pdfStorageService
  ) {}
  
  /**
   * Display the PDF export dashboard
   */
  public function index(): View
  {
    $statistics = $this->pdfStorageService->getStatistics();
    $recentPdfs = GeneratedPdf::permanent()
      ->latest()
      ->take(10)
      ->get();
    
    return view('admin.pdf-export.index', [
      'statistics' => $statistics,
      'recentPdfs' => $recentPdfs,
    ]);
  }
  
  /**
   * Display dynamic exports (factions and decks)
   */
  public function dynamicExports(): View
  {
    $factions = Faction::with(['heroes', 'cards'])
      ->orderBy('name')
      ->get();
      
    $decks = FactionDeck::with(['faction', 'heroes', 'cards'])
      ->orderBy('name')
      ->get();
    
    return view('admin.pdf-export.dynamic', [
      'factions' => $factions,
      'decks' => $decks,
    ]);
  }
  
  /**
   * Display other exports
   */
  public function otherExports(): View
  {
    // Define available custom exports
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
      // Add more custom exports as needed
    ];
    
    return view('admin.pdf-export.other', [
      'customExports' => $customExports,
    ]);
  }
  
  /**
   * Generate PDF for a faction
   */
  public function generateFaction(Request $request, Faction $faction): JsonResponse
  {
    try {
      // Generate PDF asynchronously
      $jobId = $this->pdfExportService->generateAsync(
        'faction',
        'faction',
        [
          'faction_id' => $faction->id,
          'faction_name' => $faction->name,
          'reduce_heroes' => $request->boolean('reduce_heroes', false),
          'with_gap' => $request->boolean('with_gap', true),
          'is_permanent' => true,
          'metadata' => [
            'faction_id' => $faction->id,
            'faction_name' => $faction->name,
          ],
        ],
        null, // No session ID for admin
        true  // Is permanent
      );
      
      return response()->json([
        'success' => true,
        'message' => __('admin.pdf_generation_started'),
        'jobId' => $jobId,
      ]);
      
    } catch (\Exception $e) {
      \Log::error('Failed to start faction PDF generation', [
        'faction_id' => $faction->id,
        'error' => $e->getMessage(),
      ]);
      
      return response()->json([
        'success' => false,
        'message' => __('admin.pdf_generation_failed'),
      ], 500);
    }
  }
  
  /**
   * Generate PDF for a deck
   */
  public function generateDeck(Request $request, FactionDeck $deck): JsonResponse
  {
    try {
      // Generate PDF asynchronously
      $jobId = $this->pdfExportService->generateAsync(
        'deck',
        'deck',
        [
          'deck_id' => $deck->id,
          'deck_name' => $deck->name,
          'reduce_heroes' => $request->boolean('reduce_heroes', false),
          'with_gap' => $request->boolean('with_gap', true),
          'is_permanent' => true,
          'metadata' => [
            'deck_id' => $deck->id,
            'deck_name' => $deck->name,
            'faction_id' => $deck->faction_id,
          ],
        ],
        null, // No session ID for admin
        true  // Is permanent
      );
      
      return response()->json([
        'success' => true,
        'message' => __('admin.pdf_generation_started'),
        'jobId' => $jobId,
      ]);
      
    } catch (\Exception $e) {
      \Log::error('Failed to start deck PDF generation', [
        'deck_id' => $deck->id,
        'error' => $e->getMessage(),
      ]);
      
      return response()->json([
        'success' => false,
        'message' => __('admin.pdf_generation_failed'),
      ], 500);
    }
  }
  
  /**
   * Generate all faction PDFs
   */
  public function generateAllFactions(Request $request): JsonResponse
  {
    $factions = Faction::published()->get();
    $jobIds = [];
    $failed = 0;
    
    foreach ($factions as $faction) {
      try {
        $jobId = $this->pdfExportService->generateAsync(
          'faction',
          'faction',
          [
            'faction_id' => $faction->id,
            'faction_name' => $faction->name,
            'reduce_heroes' => $request->boolean('reduce_heroes', false),
            'with_gap' => $request->boolean('with_gap', true),
            'is_permanent' => true,
            'metadata' => [
              'faction_id' => $faction->id,
              'faction_name' => $faction->name,
              'batch' => true,
            ],
          ],
          null,
          true
        );
        
        $jobIds[] = $jobId;
      } catch (\Exception $e) {
        $failed++;
        \Log::error('Failed to queue faction PDF generation', [
          'faction_id' => $faction->id,
          'error' => $e->getMessage(),
        ]);
      }
    }
    
    return response()->json([
      'success' => $failed === 0,
      'message' => __('admin.batch_generation_queued', [
        'queued' => count($jobIds),
        'failed' => $failed,
      ]),
      'jobIds' => $jobIds,
      'queued' => count($jobIds),
      'failed' => $failed,
    ]);
  }
  
  /**
   * Generate all deck PDFs
   */
  public function generateAllDecks(Request $request): JsonResponse
  {
    $decks = FactionDeck::published()->get();
    $jobIds = [];
    $failed = 0;
    
    foreach ($decks as $deck) {
      try {
        $jobId = $this->pdfExportService->generateAsync(
          'deck',
          'deck',
          [
            'deck_id' => $deck->id,
            'deck_name' => $deck->name,
            'reduce_heroes' => $request->boolean('reduce_heroes', false),
            'with_gap' => $request->boolean('with_gap', true),
            'is_permanent' => true,
            'metadata' => [
              'deck_id' => $deck->id,
              'deck_name' => $deck->name,
              'faction_id' => $deck->faction_id,
              'batch' => true,
            ],
          ],
          null,
          true
        );
        
        $jobIds[] = $jobId;
      } catch (\Exception $e) {
        $failed++;
        \Log::error('Failed to queue deck PDF generation', [
          'deck_id' => $deck->id,
          'error' => $e->getMessage(),
        ]);
      }
    }
    
    return response()->json([
      'success' => $failed === 0,
      'message' => __('admin.batch_generation_queued', [
        'queued' => count($jobIds),
        'failed' => $failed,
      ]),
      'jobIds' => $jobIds,
      'queued' => count($jobIds),
      'failed' => $failed,
    ]);
  }
  
  /**
   * Generate custom PDF
   */
  public function generateCustom(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'template' => 'required|string',
      'options' => 'array',
    ]);
    
    try {
      $jobId = $this->pdfExportService->generateAsync(
        $validated['template'],
        $validated['template'],
        array_merge([
          'is_permanent' => true,
        ], $validated['options'] ?? []),
        null,
        true
      );
      
      return response()->json([
        'success' => true,
        'message' => __('admin.pdf_generation_started'),
        'jobId' => $jobId,
      ]);
      
    } catch (\Exception $e) {
      \Log::error('Failed to start custom PDF generation', [
        'template' => $validated['template'],
        'error' => $e->getMessage(),
      ]);
      
      return response()->json([
        'success' => false,
        'message' => __('admin.pdf_generation_failed'),
      ], 500);
    }
  }
  
  /**
   * Delete a PDF
   */
  public function destroy(GeneratedPdf $pdf): RedirectResponse
  {
    try {
      $pdf->delete();
      
      return back()->with('success', __('admin.pdf_deleted_successfully'));
    } catch (\Exception $e) {
      \Log::error('Failed to delete PDF', [
        'pdf_id' => $pdf->id,
        'error' => $e->getMessage(),
      ]);
      
      return back()->with('error', __('admin.pdf_deletion_failed'));
    }
  }
  
  /**
   * Get statistics as JSON
   */
  public function statistics(): JsonResponse
  {
    $statistics = $this->pdfStorageService->getStatistics();
    
    return response()->json($statistics);
  }
  
  /**
   * Run cleanup of temporary PDFs
   */
  public function cleanup(): JsonResponse
  {
    try {
      $deleted = $this->pdfStorageService->cleanupExpired();
      
      return response()->json([
        'success' => true,
        'message' => __('admin.pdf_cleanup_completed', ['count' => $deleted]),
        'deleted' => $deleted
      ]);
    } catch (\Exception $e) {
      \Log::error('PDF cleanup failed', [
        'error' => $e->getMessage()
      ]);
      
      return response()->json([
        'success' => false,
        'message' => __('admin.pdf_cleanup_failed')
      ], 500);
    }
  }
  
  /**
   * Check job status
   */
  public function checkStatus(string $jobId): JsonResponse
  {
    $status = $this->pdfExportService->getJobStatus($jobId);
    
    if ($status['status'] === 'completed' && $status['pdf_id']) {
      $pdf = GeneratedPdf::find($status['pdf_id']);
      
      if ($pdf) {
        $status['pdf'] = [
          'id' => $pdf->id,
          'url' => $pdf->url,
          'filename' => $pdf->filename,
          'size' => $pdf->formatted_size,
        ];
      }
    }
    
    return response()->json($status);
  }
}