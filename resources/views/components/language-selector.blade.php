@props(['variant' => 'dropdown', 'size' => 'md', 'useQueryParam' => false])

@php
  $currentLocale = app()->getLocale();
  $availableLocales = config('app.available_locales', ['es']);
  $localeNames = [
    'es' => 'Español',
    'en' => 'English',
    // Puedes añadir más idiomas aquí según necesites
  ];
@endphp

<div {{ $attributes->merge(['class' => 'language-selector language-selector--' . $variant]) }}
     x-data="{ open: false }">
  
  @if($variant === 'dropdown')
    <button 
      type="button" 
      class="language-selector__toggle"
      x-on:click="open = !open"
      aria-haspopup="true"
      x-bind:aria-expanded="open">
      <span class="language-selector__current">
        <span class="language-selector__flag">{{ strtoupper(substr($currentLocale, 0, 2)) }}</span>
        <span class="language-selector__name">{{ $localeNames[$currentLocale] ?? $currentLocale }}</span>
      </span>
      <x-icon name="chevron-down" class="language-selector__icon" />
    </button>

    <div 
      class="language-selector__dropdown" 
      x-show="open" 
      x-on:click.outside="open = false"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 scale-95"
      x-transition:enter-end="opacity-100 scale-100"
      x-transition:leave="transition ease-in duration-100"
      x-transition:leave-start="opacity-100 scale-100" 
      x-transition:leave-end="opacity-0 scale-95">
      <ul class="language-selector__list">
        @foreach($availableLocales as $locale)
          <li class="language-selector__item {{ $locale === $currentLocale ? 'language-selector__item--active' : '' }}">
            <a 
              href="{{ $useQueryParam ? request()->fullUrlWithQuery(['locale' => $locale]) : route('language.change', $locale) }}" 
              class="language-selector__link"
              @if($locale === $currentLocale) aria-current="true" @endif>
              <span class="language-selector__flag">{{ strtoupper(substr($locale, 0, 2)) }}</span>
              <span class="language-selector__name">{{ $localeNames[$locale] ?? $locale }}</span>
            </a>
          </li>
        @endforeach
      </ul>
    </div>
  @elseif($variant === 'buttons')
    <div class="language-selector__buttons">
      @foreach($availableLocales as $locale)
        <a 
          href="{{ $useQueryParam ? request()->fullUrlWithQuery(['locale' => $locale]) : route('language.change', $locale) }}" 
          class="language-selector__button {{ $locale === $currentLocale ? 'language-selector__button--active' : '' }}"
          @if($locale === $currentLocale) aria-current="true" @endif>
          {{ strtoupper(substr($locale, 0, 2)) }}
        </a>
      @endforeach
    </div>
  @endif
</div>