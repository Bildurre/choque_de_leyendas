@props([
  'name',
  'label' => null,
  'values' => [],
  'required' => false,
  'locales' => array_keys(config('laravellocalization.supportedLocales', ['es' => []])),
])

@php
  // Obtener el idioma actual de la aplicación
  $currentLocale = app()->getLocale();
@endphp

<div class="form-field form-field--multilingual form-field--wysiwyg">
  @if($label)
    <x-form.label :for="$name.'_'.$currentLocale" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  <x-form.language-tabs :locales="$locales" :field-name="$name">
    @foreach($locales as $locale)
      <div class="language-tabs__panel {{ $locale === $currentLocale ? 'language-tabs__panel--active' : '' }}" data-locale="{{ $locale }}">
        <textarea
          name="{{ $name }}[{{ $locale }}]"
          id="{{ $name }}_{{ $locale }}"
          class="wysiwyg-editor"
          data-images-only="true"
        >{{ $values[$locale] ?? old($name.'.'.$locale, '') }}</textarea>
      </div>
    @endforeach
  </x-form.language-tabs>
  
  <x-form.error :name="$name.'.*'" />
</div>