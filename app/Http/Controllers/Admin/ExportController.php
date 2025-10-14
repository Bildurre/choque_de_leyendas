<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ExportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ExportController extends Controller
{
  private ExportService $exportService;

  public function __construct(ExportService $exportService)
  {
    $this->exportService = $exportService;
  }

  public function index(): View
  {
    $databaseInfo = $this->exportService->getDatabaseInfo();
    $exports = $this->exportService->listExports();
    
    return view('admin.export.index', [
      'databaseInfo' => $databaseInfo,
      'exports' => $exports
    ]);
  }

  public function export(Request $request)
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
}