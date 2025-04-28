@props([
  'name',
  'label' => null,
  'required' => false,
  'value' => [],
  'placeholder' => '',
  'help' => null,
  'type' => 'text',
  'locales' => null
])

@php
  $availableLocales = $locales ?? config('app.available_locales', ['es']);
  $currentLocale = app()->getLocale();
  $id = $name . '_' . uniqid();
  
  // Normalizar el valor para que sea un array asociativo por locale
  if (!is_array($value)) {
    $value = [$currentLocale => $value];
  }
@endphp

<x-form.group>
  @if($label)
    <x-form.label :for="$id" :required="$required">
      {{ $label }}
    </x-form.label>
  @endif
  
  <div class="translate-field" x-data="{ activeLocale: '{{ $currentLocale }}' }">
    <div class="translate-field__tabs">
      @foreach($availableLocales as $locale)
        <button 
          type="button" 
          class="translate-field__tab" 
          x-bind:class="{ 'translate-field__tab--active': activeLocale === '{{ $locale }}' }"
          x-on:click="activeLocale = '{{ $locale }}'">
          {{ strtoupper(substr($locale, 0, 2)) }}
        </button>
      @endforeach
    </div>
    
    <div class="translate-field__content">
      @foreach($availableLocales as $locale)
        <div 
          class="translate-field__input-wrapper" 
          x-bind:class="{ 'translate-field__input-wrapper--active': activeLocale === '{{ $locale }}' }"
          x-show="activeLocale === '{{ $locale }}'">
          <input 
            type="{{ $type }}" 
            id="{{ $id }}_{{ $locale }}" 
            name="{{ $name }}[{{ $locale }}]" 
            value="{{ old($name . '.' . $locale, $value[$locale] ?? '') }}" 
            class="form-input @error($name.'.'.$locale) is-invalid @enderror"
            placeholder="{{ $placeholder ? $placeholder . ' (' . locale_name($locale) . ')' : '' }}"
            @if($locale === $currentLocale && $required) required @endif
            {{ $attributes }}
          >
          @error($name.'.'.$locale)
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      @endforeach
    </div>
  </div>
  
  <x-form.help :text="$help" />
</x-form.group>