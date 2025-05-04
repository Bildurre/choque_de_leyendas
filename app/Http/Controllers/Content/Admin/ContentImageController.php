<?php

namespace App\Http\Controllers\Content\Admin;

use App\Http\Controllers\Controller;
use App\Services\Content\ContentImageService;
use Illuminate\Http\Request;

class ContentImageController extends Controller
{
  protected $contentImageService;

  /**
   * Create a new controller instance.
   *
   * @param ContentImageService $contentImageService
   */
  public function __construct(ContentImageService $contentImageService)
  {
    $this->contentImageService = $contentImageService;
  }

  /**
   * Get a list of all available images for the content editor.
   */
  public function index()
  {
    $images = $this->contentImageService->getAvailableContentImages();
    return response()->json($images);
  }

  /**
   * Upload a new image for the content editor.
   */
  public function store(Request $request)
  {
    $request->validate([
      'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    try {
      $image = $request->file('image');
      $path = $this->contentImageService->storeBlockImage($image);
      
      if ($path) {
        return response()->json([
          'success' => true,
          'path' => $path,
          'url' => asset('storage/' . $path),
          'title' => pathinfo($path, PATHINFO_FILENAME),
        ]);
      }
      
      return response()->json([
        'success' => false,
        'message' => 'Error al subir la imagen'
      ], 500);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Error al subir la imagen: ' . $e->getMessage()
      ], 500);
    }
  }

  /**
   * Delete an image from the content editor.
   */
  public function destroy(Request $request)
  {
    $request->validate([
      'path' => 'required|string',
    ]);

    try {
      $path = $request->input('path');
      $success = $this->contentImageService->deleteBlockImage($path);
      
      return response()->json([
        'success' => $success,
        'message' => $success ? 'Imagen eliminada correctamente' : 'No se pudo eliminar la imagen'
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Error al eliminar la imagen: ' . $e->getMessage()
      ], 500);
    }
  }
}