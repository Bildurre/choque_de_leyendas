<div class="language-selector">
  @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    <a href="{{ localized_current_url($localeCode) }}" 
       class="language-button {{ LaravelLocalization::getCurrentLocale() == $localeCode ? 'is-active' : '' }}"
       rel="alternate" 
       hreflang="{{ $localeCode }}">
        {{ strtoupper($localeCode) }}
    </a>
  @endforeach
</div>