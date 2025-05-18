@props(['changeLocaleOnly' => false])

<div class="language-selector">
  @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    @if($changeLocaleOnly)
      <form action="{{ route('set-locale') }}" method="POST" class="locale-form">
        @csrf
        <input type="hidden" name="locale" value="{{ $localeCode }}">
        <input type="hidden" name="redirect_url" value="{{ url()->current() }}">
        <button type="submit" 
          class="language-button {{ App::getLocale() == $localeCode ? 'is-active' : '' }}">
            {{ strtoupper($localeCode) }}
        </button>
      </form>
    @else
      <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" 
         class="language-button {{ App::getLocale() == $localeCode ? 'is-active' : '' }}"
         rel="alternate" 
         hreflang="{{ $localeCode }}">
          {{ strtoupper($localeCode) }}
      </a>
    @endif
  @endforeach
</div>