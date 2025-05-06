@props([
  'name',
  'label' => null,
  'placeholder' => '',
  'values' => [],
  'required' => false,
  'locales' => config('app.available_locales', ['es']),
  'defaultLocale' => app()->getLocale()
])

<div class="form-field form-field--multilingual">
  @if($label)
    <x-form.label :for="$name.'_'.$defaultLocale" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  <x-form.language-tabs :locales="$locales" :default-locale="$defaultLocale" :field-name="$name">
    @foreach($locales as $locale)
      <div class="language-tabs__panel {{ $locale === $defaultLocale ? 'language-tabs__panel--active' : '' }}" data-locale="{{ $locale }}">
        <input 
          type="text"
          name="{{ $name }}[{{ $locale }}]"
          id="{{ $name }}_{{ $locale }}"
          value="{{ $values[$locale] ?? old($name.'.'.$locale, '') }}"
          placeholder="{{ $placeholder }}"
          class="form-input"
          {{ $locale === $defaultLocale && $required ? 'required' : '' }}
        />
      </div>
    @endforeach
  </x-form.language-tabs>
  
  <x-form.error :name="$name.'.*'" />
</div>