@props(['changeLocaleOnly' => false])

<div class="language-selector">
  @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    <form action="{{ route('set-locale') }}" method="POST" class="locale-form">
      @csrf
      <input type="hidden" name="locale" value="{{ $localeCode }}">
      @php
        if (!$changeLocaleOnly) {
          // Frontend: use localized URL (LaravelLocalization handles the routing)
          $currentUrl = LaravelLocalization::getLocalizedURL($localeCode);
        } else {
          // Admin: preserve current URL and query parameters
          $currentUrl = url()->current();
          $queryParams = request()->query();
          if (!empty($queryParams)) {
            $currentUrl .= '?' . http_build_query($queryParams);
          }
        }
      @endphp
      <input type="hidden" name="redirect_url" value="{{ $currentUrl }}">
      <button type="submit" 
        class="language-button {{ App::getLocale() == $localeCode ? 'is-active' : '' }}">
          {{ strtoupper($localeCode) }}
      </button>
    </form>
  @endforeach
</div>