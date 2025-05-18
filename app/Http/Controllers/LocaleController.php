<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
  public function setLocale(Request $request)
  {
    $locale = $request->input('locale');
    $redirectUrl = $request->input('redirect_url', url('/'));
    
    // Validar que el locale es válido
    $supportedLocales = config('laravellocalization.supportedLocales');
    if (!isset($supportedLocales[$locale])) {
      return redirect()->back();
    }
    
    // Guardar el locale en la sesión
    Session::put('locale', $locale);
    
    // También establecer una cookie para redundancia
    return redirect($redirectUrl)->withCookie(cookie('locale', $locale, 60*24*365));
  }
}