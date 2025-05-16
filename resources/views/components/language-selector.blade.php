<div class="language-selector">
  @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    @php
      // Detectar la ruta actual y el modelo
      $currentRoute = Route::current();
      $routeName = $currentRoute ? $currentRoute->getName() : null;
      $url = null;
      
      // Crear URL personalizada según el tipo de ruta
      if ($routeName === 'public.factions.show') {
        $faction = Route::current()->parameter('faction');
        $translatedSlug = $faction->getTranslation('slug', $localeCode, false);
        $baseRoute = __('routes.factions', [], $localeCode);
        $url = url($localeCode . '/' . $baseRoute . '/' . $translatedSlug);
      }
      elseif ($routeName === 'public.heroes.show') {
        $hero = Route::current()->parameter('hero');
        $translatedSlug = $hero->getTranslation('slug', $localeCode, false);
        $baseRoute = __('routes.heroes', [], $localeCode);
        $url = url($localeCode . '/' . $baseRoute . '/' . $translatedSlug);
      }
      elseif ($routeName === 'public.cards.show') {
        $card = Route::current()->parameter('card');
        $translatedSlug = $card->getTranslation('slug', $localeCode, false);
        $baseRoute = __('routes.cards', [], $localeCode);
        $url = url($localeCode . '/' . $baseRoute . '/' . $translatedSlug);
      }
      elseif ($routeName === 'public.faction-decks.show') {
        $factionDeck = Route::current()->parameter('factionDeck');
        $translatedSlug = $factionDeck->getTranslation('slug', $localeCode, false);
        $baseRoute = __('routes.faction-decks', [], $localeCode);
        $url = url($localeCode . '/' . $baseRoute . '/' . $translatedSlug);
      }
      elseif ($routeName === 'content.page') {
        $page = Route::current()->parameter('page');
        $translatedSlug = $page->getTranslation('slug', $localeCode, false);
        $url = url($localeCode . '/' . $translatedSlug);
      }
      // Para el resto de rutas, usamos el método estándar
      else {
        $url = LaravelLocalization::getLocalizedURL($localeCode);
      }
    @endphp
    
    <a href="{{ $url }}" 
       class="language-button {{ LaravelLocalization::getCurrentLocale() == $localeCode ? 'is-active' : '' }}"
       rel="alternate" 
       hreflang="{{ $localeCode }}">
        {{ strtoupper($localeCode) }}
    </a>
  @endforeach
</div>