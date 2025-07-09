@props([
  'name',
  'label' => null,
  'required' => false,
  'currentImages' => [],
  'locales' => array_keys(config('laravellocalization.supportedLocales', ['es' => []])),
])

@php
  // Get current application locale
  $currentLocale = app()->getLocale();
  
  // Ensure currentImages is an array
  if (!is_array($currentImages)) {
    $currentImages = [];
  }
@endphp

<div class="form-field form-field--multilingual">
  @if($label)
    <x-form.label :for="$name.'_'.$currentLocale" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  <x-form.language-tabs :locales="$locales" :field-name="$name">
    @foreach($locales as $locale)
      <div class="language-tabs__panel {{ $locale === $currentLocale ? 'language-tabs__panel--active' : '' }}" data-locale="{{ $locale }}">
        <div class="image-upload {{ isset($currentImages[$locale]) && $currentImages[$locale] ? 'has-image' : '' }}" data-locale="{{ $locale }}">
          <!-- Drag and drop area with overlay -->
          <div class="image-upload__dropzone">
            <!-- Preview container (visible only if there's an image) -->
            <div class="image-upload__preview-container" {{ !isset($currentImages[$locale]) || !$currentImages[$locale] ? 'style=display:none' : '' }}>
              @if(isset($currentImages[$locale]) && $currentImages[$locale])
                <img src="{{ asset('storage/' . $currentImages[$locale]) }}" alt="{{ $label ?? 'Current image' }} - {{ strtoupper($locale) }}" class="image-upload__preview">
              @else
                <img src="" alt="{{ $label ?? 'Current image' }} - {{ strtoupper($locale) }}" class="image-upload__preview">
              @endif
              
              <!-- Button to remove image -->
              <button type="button" class="image-upload__remove-btn action-button action-button--delete" title="{{ __('admin.remove_image') }}">
                <x-icon name="trash" size="sm" />
              </button>
              
              <!-- Hidden input to indicate deletion -->
              <input type="hidden" name="remove_image_{{ $locale }}" value="0" class="image-upload__remove-flag">
            </div>
            
            <!-- Placeholder (visible only when there's no image) -->
            <div class="image-upload__placeholder">
              <div class="image-upload__icon">
                <x-icon name="upload" size="md" />
              </div>
              <div class="image-upload__text">
                <p>{{ __('admin.drag_image_here') }}</p>
                <p class="image-upload__browse-text">{{ __('admin.or_click_to_browse') }}</p>
              </div>
            </div>
            
            <!-- Hidden file input -->
            <input 
              type="file" 
              name="image_{{ $locale }}" 
              id="{{ $name }}_{{ $locale }}" 
              class="image-upload__input"
              accept="image/*"
              {{ $locale === $currentLocale && $required ? 'required' : '' }}
            >
          </div>
        </div>
      </div>
    @endforeach
  </x-form.language-tabs>
  
  <x-form.error :name="'image_*'" />
</div>