@props([
  'name',
  'label' => 'wysiwyg',
  'value' => '',
  'required' => false,
  'rows' => 10,
  'placeholder' => '',
  'imageList' => null,
  'advanced' => false,
  'help' => null
])

@php
  if ($imageList === null) {
    $imageList = app(App\Services\WysiwygImageService::class)->getAvailableImages();
  }
  
  // Generamos un ID único para el elemento de configuración
  $configId = $name . '-config-' . uniqid();
@endphp

<x-form.group>
  <x-form.label :for="$name" :required="$required">
    {{ $label }}
  </x-form.label>
  
  <textarea 
    id="{{ $name }}" 
    name="{{ $name }}" 
    class="wysiwyg-editor @error($name) is-invalid @enderror"
    rows="{{ $rows }}"
    @if($required) required @endif
    placeholder="{{ $placeholder }}"
    {{ $attributes }}
  >{{ old($name, $value) }}</textarea>
  
  <x-form.error :name="$name" />
  
  <x-form.help :text="$help" />
  
  <script type="application/json" id="{{ $name }}-image-list">
    @json($imageList)
  </script>
  
  <script type="application/json" id="{{ $configId }}">
    {
      "advanced": {{ $advanced ? 'true' : 'false' }}
    }
  </script>
</x-form.group>