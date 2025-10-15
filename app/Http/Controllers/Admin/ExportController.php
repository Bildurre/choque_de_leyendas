<?php

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Admin\ExportService;
use Illuminate\Http\RedirectResponse;

class ExportController extends Controller
{
  private ExportService $exportService;

  public function __construct(ExportService $exportService)
  {
    $this->exportService = $exportService;
  }

  public function database(): View
  {
    $databaseInfo = $this->exportService->getDatabaseInfo();
    $exports = $this->exportService->listExports('database');
    
    return view('admin.export.database', [
      'databaseInfo' => $databaseInfo,
      'exports' => $exports
    ]);
  }

  public function json(): View
  {
    $exports = $this->exportService->listExports('json');
    $factions = \App\Models\Faction::withoutTrashed()
      ->orderBy('name')
      ->get();
    
    return view('admin.export.json', [
      'exports' => $exports,
      'factions' => $factions
    ]);
  }

  public function exportDatabase(Request $request)
  {
    $validated = $request->validate([
      'include_data' => 'boolean',
      'compress' => 'boolean',
      'exclude_tables' => 'array',
      'exclude_tables.*' => 'string'
    ]);

    $result = $this->exportService->exportDatabase([
      'include_data' => $validated['include_data'] ?? true,
      'compress' => $validated['compress'] ?? false,
      'exclude_tables' => $validated['exclude_tables'] ?? []
    ]);

    if ($result['success']) {
      return $this->exportService->downloadFile($result['filename']);
    }

    return redirect()->back()->with('error', __('export.export_error') . ': ' . $result['error']);
  }

  public function download(string $filename)
  {
    return $this->exportService->downloadFile($filename);
  }

  public function deleteSingle(string $filename): RedirectResponse
  {
    $deleted = $this->exportService->deleteExports([$filename]);

    if ($deleted > 0) {
      return redirect()->back()->with('success', __('export.deleted_single_success'));
    }

    return redirect()->back()->with('error', __('export.delete_error'));
  }

  public function deleteAll(): RedirectResponse
  {
    $deleted = $this->exportService->deleteAllExports();

    return redirect()->back()->with('success', __('export.deleted_all_success', ['count' => $deleted]));
  }

  public function downloadAll(Request $request)
  {
    $exports = $this->exportService->listExports();
    $filenames = array_column($exports, 'filename');

    if (empty($filenames)) {
      return redirect()->back()->with('error', __('export.no_exports_to_download'));
    }

    if (count($filenames) === 1) {
      return $this->exportService->downloadFile($filenames[0]);
    }

    try {
      $zipFilename = $this->exportService->downloadMultiple($filenames);
      
      $response = $this->exportService->downloadFile($zipFilename);
      
      register_shutdown_function(function() use ($zipFilename) {
        $filepath = storage_path('app/exports/' . $zipFilename);
        if (file_exists($filepath)) {
          @unlink($filepath);
        }
      });
      
      return $response;
      
    } catch (\Exception $exception) {
      return redirect()->back()->with('error', __('export.download_error') . ': ' . $exception->getMessage());
    }
  }

  public function restoreDatabase(string $filename)
  {
    $result = $this->exportService->restoreDatabase($filename);

    if ($result['success']) {
      // Invalidar la sesión actual ya que los usuarios se han restaurado
      Auth::logout();
      request()->session()->invalidate();
      request()->session()->regenerateToken();
      
      // Redirigir al login con mensaje de éxito
      return redirect()->route('login')
        ->with('success', __('export.restore_success_logout'));
    }

    return redirect()->back()->with('error', __('export.restore_error') . ': ' . $result['error']);
  }

  public function uploadDatabase(Request $request)
  {
    $validated = $request->validate([
      'database_file' => [
        'required',
        'file',
        'max:102400', // 100MB max
        function ($attribute, $value, $fail) {
          $extension = strtolower($value->getClientOriginalExtension());
          $mimeType = $value->getMimeType();
          
          // Aceptar ZIP
          if ($extension === 'zip') {
            if (!in_array($mimeType, ['application/zip', 'application/x-zip-compressed', 'application/x-zip'])) {
              $fail(__('export.invalid_zip_file'));
            }
            return;
          }
          
          // Aceptar SQL (los archivos SQL pueden tener varios MIME types)
          if ($extension === 'sql') {
            $validMimeTypes = [
              'application/sql',
              'application/x-sql',
              'text/plain',
              'text/x-sql',
              'application/octet-stream'
            ];
            
            if (!in_array($mimeType, $validMimeTypes)) {
              $fail(__('export.invalid_sql_file'));
            }
            return;
          }
          
          $fail(__('export.invalid_file_type'));
        }
      ]
    ]);

    $result = $this->exportService->uploadDatabaseFile($validated['database_file']);

    if ($result['success']) {
      return redirect()->back()->with('success', __('export.upload_success', ['filename' => $result['filename']]));
    }

    return redirect()->back()->with('error', __('export.upload_error') . ': ' . $result['error']);
  }

  public function exportCards()
  {
    $result = $this->exportService->exportCards();

    if ($result['success']) {
      return response()->download($result['filepath'])->deleteFileAfterSend(true);
    }

    return redirect()->back()->with('error', __('export.export_error') . ': ' . $result['error']);
  }

  public function exportHeroes()
  {
    $result = $this->exportService->exportHeroes();

    if ($result['success']) {
      return response()->download($result['filepath'])->deleteFileAfterSend(true);
    }

    return redirect()->back()->with('error', __('export.export_error') . ': ' . $result['error']);
  }

  public function exportCounters()
  {
    $result = $this->exportService->exportCounters();

    if ($result['success']) {
      return response()->download($result['filepath'])->deleteFileAfterSend(true);
    }

    return redirect()->back()->with('error', __('export.export_error') . ': ' . $result['error']);
  }

  public function exportClasses()
  {
    $result = $this->exportService->exportClasses();

    if ($result['success']) {
      return response()->download($result['filepath'])->deleteFileAfterSend(true);
    }

    return redirect()->back()->with('error', __('export.export_error') . ': ' . $result['error']);
  }

  public function exportFaction(Request $request)
  {
    $validated = $request->validate([
      'faction_id' => 'required|exists:factions,id'
    ]);

    $faction = \App\Models\Faction::findOrFail($validated['faction_id']);
    $result = $this->exportService->exportFaction($faction);

    if ($result['success']) {
      return response()->download($result['filepath'])->deleteFileAfterSend(true);
    }

    return redirect()->back()->with('error', __('export.export_error') . ': ' . $result['error']);
  }

  public function exportAllFactions()
  {
    $result = $this->exportService->exportAllFactions();

    if ($result['success']) {
      if (isset($result['zip'])) {
        // Multiple factions - download ZIP
        $response = response()->download($result['filepath'])->deleteFileAfterSend(true);
        return $response;
      } else {
        // Single faction - download JSON directly
        return response()->download($result['filepath'])->deleteFileAfterSend(true);
      }
    }

    return redirect()->back()->with('error', __('export.export_error') . ': ' . $result['error']);
  }
}