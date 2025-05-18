<div class="language-selector">
  @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" 
       class="language-button {{ LaravelLocalization::getCurrentLocale() == $localeCode ? 'is-active' : '' }}"
       rel="alternate" 
       hreflang="{{ $localeCode }}">
        {{ strtoupper($localeCode) }}
    </a>
  @endforeach
</div>