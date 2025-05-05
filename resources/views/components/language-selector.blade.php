<div class="language-selector">
  @foreach(config('app.available_locales', ['es']) as $locale)
    <a 
      href="{{ route('language.change', $locale) }}" 
      class="language-button {{ app()->getLocale() === $locale ? 'is-active' : '' }}"
    >
      {{ strtoupper($locale) }}
    </a>
  @endforeach
</div>