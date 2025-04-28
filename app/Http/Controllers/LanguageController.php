<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Change the application language.
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change($locale)
    {
        // Validate the locale is supported
        $availableLocales = config('app.available_locales', ['es']);
        
        if (!in_array($locale, $availableLocales)) {
            $locale = config('app.fallback_locale', 'es');
        }
        
        // Store the locale in session
        Session::put('locale', $locale);
        
        // Redirect back to previous page
        return redirect()->back();
    }
}