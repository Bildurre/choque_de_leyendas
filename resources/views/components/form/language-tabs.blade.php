@props([
  'locales' => config('app.available_locales', ['es']),
  'defaultLocale' => app()->getLocale(),
  'fieldName' => '',
])

<div class="language-tabs" data-field="{{ $fieldName }}">
  <div class="language-tabs__header">
    @foreach($locales as $locale)
      <button
        type="button"
        class="language-tabs__tab {{ $locale === $defaultLocale ? 'language-tabs__tab--active' : '' }}" 
        data-locale="{{ $locale }}">
        {{ strtoupper($locale) }}
      </button>
    @endforeach
  </div>
  
  <div class="language-tabs__content">
    {{ $slot }}
  </div>
</div>