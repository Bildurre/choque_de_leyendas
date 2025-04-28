@props([
  'name',
  'label' => null,
  'required' => false,
  'value' => [],
  'placeholder' => '',
  'help' => null,
  'rows' => 10,
  'advanced' => false,
  'imageList' => null,
  'locales' => null
])

@php
  $availableLocales = $locales ?? config('app.available_locales', ['es']);
  $currentLocale = app()->getLocale();
  $baseId = $name . '_' . uniqid();
  
  // Normalizar el valor para que sea un array asociativo por locale
  if (!is_array($value)) {
    $value = [$currentLocale => $value];
  }
  
  // Obtener lista de imágenes si no se proporciona
  if ($imageList === null) {
    $imageList = app(App\Services\WysiwygImageService::class)->getAvailableImages();
  }
@endphp

<x-form.group>
  @if($label)
    <x-form.label :for="$baseId" :required="$required">
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
        @php
          $localizedId = $baseId . '_' . $locale;
          $configId = $localizedId . '-config-' . uniqid();
        @endphp
        
        <div 
          class="translate-field__input-wrapper" 
          x-bind:class="{ 'translate-field__input-wrapper--active': activeLocale === '{{ $locale }}' }"
          x-show="activeLocale === '{{ $locale }}'">
          <textarea 
            id="{{ $localizedId }}" 
            name="{{ $name }}[{{ $locale }}]" 
            class="wysiwyg-editor @error($name.'.'.$locale) is-invalid @enderror"
            rows="{{ $rows }}"
            @if($locale === $currentLocale && $required) required @endif
            placeholder="{{ $placeholder ? $placeholder . ' (' . locale_name($locale) . ')' : '' }}"
            {{ $attributes }}
          >{{ old($name . '.' . $locale, $value[$locale] ?? '') }}</textarea>
          
          @error($name.'.'.$locale)
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          
          <script type="application/json" id="{{ $localizedId }}-image-list">
            @json($imageList)
          </script>
          
          <script type="application/json" id="{{ $configId }}">
            {
              "advanced": {{ $advanced ? 'true' : 'false' }}
            }
          </script>
        </div>
      @endforeach
    </div>
  </div>
  
  <x-form.help :text="$help" />
  
  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Initialize WYSIWYG editors for translated fields
        const initTranslateWysiwyg = function() {
          @foreach($availableLocales as $locale)
            if (typeof initWysiwygEditors === 'function' && document.getElementById('{{ $baseId }}_{{ $locale }}')) {
              initWysiwygEditors();
            }
          @endforeach
        };
        
        // Initial initialization
        initTranslateWysiwyg();
        
        // Re-initialize when tab changes
        document.querySelectorAll('.translate-field__tab').forEach(tab => {
          tab.addEventListener('click', () => {
            setTimeout(() => {
              if (typeof tinymce !== 'undefined') {
                tinymce.remove(`textarea#{{ $baseId }}_${tab.innerText.toLowerCase().trim()}`);
                initTranslateWysiwyg();
              }
            }, 100);
          });
        });
      });
    </script>
  @endpush
</x-form.group>