<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PageController extends Controller
{
    /**
     * Display the content home page.
     */
    public function index(): View
    {
        $locale = app()->getLocale();
        
        // Try to find a home page
        $homePage = Page::published()
            ->whereJsonContains("slug->{$locale}", 'home')
            ->first();
        
        if ($homePage) {
            return $this->show($homePage);
        }
        
        // Otherwise, show a list of root pages
        $pages = Page::published()->root()->orderBy('order')->get();
        return view('content.index', compact('pages'));
    }

    /**
     * Display a specific page.
     */
    public function show(Page $page): View
    {
        if (!$page->isPublished()) {
            abort(404, 'Page not found');
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