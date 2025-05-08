@props([
  'name',
  'label' => null,
  'values' => [],
  'required' => false,
  'locales' => config('app.available_locales', ['es']),
  'defaultLocale' => app()->getLocale()
])

<div class="form-field form-field--multilingual form-field--wysiwyg">
  @if($label)
    <x-form.label :for="$name.'_'.$defaultLocale" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  <x-form.language-tabs :locales="$locales" :default-locale="$defaultLocale" :field-name="$name">
    @foreach($locales as $locale)
      <div class="language-tabs__panel {{ $locale === $defaultLocale ? 'language-tabs__panel--active' : '' }}" data-locale="{{ $locale }}">
        <textarea
          name="{{ $name }}[{{ $locale }}]"
          id="{{ $name }}_{{ $locale }}"
          class="wysiwyg-editor"
          data-images-only="true"
          {{ $locale === $defaultLocale && $required ? 'required' : '' }}
        >{{ $values[$locale] ?? old($name.'.'.$locale, '') }}</textarea>
      </div>
    @endforeach
  </x-form.language-tabs>
  
  <x-form.error :name="$name.'.*'" />
</div>