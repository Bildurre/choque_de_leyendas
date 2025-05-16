<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display a specific page.
     */
    public function show(Page $page): View
    {
        // Verificar si la página está publicada
        if (!$page->is_published) {
            abort(404);
        }
        
        // Verify if the template exists, use default if not
        $template = $page->template ?: 'default';
        $view = "content.templates.{$template}";
        
        if (!view()->exists($view)) {
            $view = 'content.templates.default';
        }
        
        return view($view, compact('page'));
    }
}