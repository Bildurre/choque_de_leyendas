@props(['locales' => null, 'defaultLocale' => null])

@php
  $availableLocales = $locales ?? config('app.available_locales', ['es']);
  $currentLocale = $defaultLocale ?? app()->getLocale();
@endphp

<div {{ $attributes->merge(['class' => 'translate-tabs']) }} x-data="{ activeTab: '{{ $currentLocale }}' }">
  <div class="translate-tabs__header">
    @foreach($availableLocales as $locale)
      <button 
        type="button"
        class="translate-tabs__tab" 
        x-bind:class="{ 'translate-tabs__tab--active': activeTab === '{{ $locale }}' }"
        x-on:click="activeTab = '{{ $locale }}'">
        <span class="translate-tabs__locale">{{ strtoupper(substr($locale, 0, 2)) }}</span>
        <span class="translate-tabs__name">{{ locale_name($locale) }}</span>
      </button>
    @endforeach
  </div>
  
  <div class="translate-tabs__content">
    @foreach($availableLocales as $locale)
      <div 
        class="translate-tabs__pane" 
        x-bind:class="{ 'translate-tabs__pane--active': activeTab === '{{ $locale }}' }"
        x-show="activeTab === '{{ $locale }}'">
        {{ $slot }}
      </div>
    @endforeach
  </div>
</div>