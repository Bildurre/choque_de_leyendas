<div x-data="{ open: false }" class="language-selector">
  <button @click="open = !open" type="button" class="language-current">
    {{ strtoupper(app()->getLocale()) }}
    <span class="language-icon">
      <x-core.icon name="chevron-down" />
    </span>
  </button>
  
  <div x-show="open" @click.away="open = false" class="language-dropdown">
    @foreach(config('app.available_locales', ['es']) as $locale)
      @if($locale !== app()->getLocale())
        <a href="{{ route('language.change', $locale) }}" class="language-option">
          {{ strtoupper($locale) }} - {{ locale_name($locale) }}
        </a>
      @endif
    @endforeach
  </div>
</div>