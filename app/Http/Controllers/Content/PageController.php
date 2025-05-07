<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
  /**
   * Display the content home page.
   */
  public function index(): View
  {
    // This could be a dedicated home page or a list of root pages
    $homePage = Page::published()->where('slug', 'home')->first();
    
    if ($homePage) {
      return $this->show($homePage->slug);
    }
    
    $pages = Page::published()->root()->orderBy('order')->get();
    return view('content.index', compact('pages'));
  }

  /**
   * Display a specific page by slug.
   */
  public function show(string $slug): View
  {
    $locale = app()->getLocale();
    
    // Esta consulta puede ser complicada con JSON, intentemos un enfoque diferente
    $pages = Page::published()->get();
    $page = null;
    
    foreach ($pages as $p) {
      if ($p->getTranslation('slug', $locale, false) === $slug) {
        $page = $p;
        break;
      }
    }
    
    if (!$page) {
      abort(404, 'Página no encontrada');
    }
    
    // Verificar si existe la plantilla, usar default si no
    $template = $page->template ?: 'default';
    $view = "content.templates.{$template}";
    
    if (!view()->exists($view)) {
      $view = 'content.templates.default';
    }
    
    return view($view, compact('page'));
  }
}