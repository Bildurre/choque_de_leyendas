@props([
  'locales' => array_keys(config('laravellocalization.supportedLocales', ['es' => []])),
  'fieldName' => '',
])

<div class="language-tabs" data-field="{{ $fieldName }}">
  <div class="language-tabs__header">
    @foreach($locales as $locale)
      <button
        type="button"
        class="language-tabs__tab {{ $locale === app()->getLocale() ? 'language-tabs__tab--active' : '' }}" 
        data-locale="{{ $locale }}">
        {{ strtoupper($locale) }}
      </button>
    @endforeach
  </div>
  
  <div class="language-tabs__content">
    {{ $slot }}
  </div>
</div>