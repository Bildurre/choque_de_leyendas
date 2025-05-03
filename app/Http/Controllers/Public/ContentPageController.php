<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Public\ContentPageService;
use Illuminate\View\View;

class ContentPageController extends Controller
{
  public function __construct(
    protected ContentPageService $service
  ) {}

  public function home(): View
  {
    $page = $this->service->getPublishedHome();
    
    if (!$page) {
      abort(404, 'Página de inicio no encontrada');
    }
    
    return view('public.content.page', compact('page'));
  }

  public function show(string $slug): View
  {
    $page = $this->service->getPublishedBySlug($slug);
    
    if (!$page) {
      abort(404, 'Página no encontrada');
    }
    
    return view('public.content.page', compact('page'));
  }

  public function rules(): View
  {
    $page = $this->service->getPublishedRulesPage();
    
    if (!$page) {
      abort(404, 'Página de reglas no encontrada');
    }
    
    return view('public.content.page', compact('page'));
  }

  public function components(): View
  {
    $page = $this->service->getPublishedComponentsPage();
    
    if (!$page) {
      abort(404, 'Página de componentes no encontrada');
    }
    
    return view('public.content.page', compact('page'));
  }

  public function annexes(): View
  {
    $page = $this->service->getPublishedAnnexesPage();
    
    if (!$page) {
      abort(404, 'Página de anexos no encontrada');
    }
    
    return view('public.content.page', compact('page'));
  }
}