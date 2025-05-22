@props(['changeLocaleOnly' => false])

<div class="language-selector">
  @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
    <form action="{{ route('set-locale') }}" method="POST" class="locale-form">
      @csrf
      <input type="hidden" name="locale" value="{{ $localeCode }}">
      <input type="hidden" name="redirect_url" value="{{ !$changeLocaleOnly ? LaravelLocalization::getLocalizedURL($localeCode) : url()->current(); }}">
      <button type="submit" 
        class="language-button {{ App::getLocale() == $localeCode ? 'is-active' : '' }}">
          {{ strtoupper($localeCode) }}
      </button>
    </form>
  @endforeach
</div>