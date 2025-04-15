@props([
  'name',
  'value' => '',
  'required' => false,
  'rows' => 10,
  'placeholder' => '',
  'imageList' => null,
  'advanced' => false
])

@php
  if ($imageList === null) {
    $imageList = app(App\Services\WysiwygImageService::class)->getAvailableImages();
  }
  
  // Generamos un ID único para el elemento de configuración
  $configId = $name . '-config-' . uniqid();
@endphp

<div class="wysiwyg-editor-container">
  <textarea 
    id="{{ $name }}" 
    name="{{ $name }}" 
    class="wysiwyg-editor @error($name) is-invalid @enderror"
    rows="{{ $rows }}"
    @if($required) required @endif
    placeholder="{{ $placeholder }}"
    {{ $attributes }}
  >{{ old($name, $value) }}</textarea>
  
  @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
  
  <script type="application/json" id="{{ $name }}-image-list">
    @json($imageList)
  </script>
  
  <script type="application/json" id="{{ $configId }}">
    {
      "advanced": {{ $advanced ? 'true' : 'false' }}
    }
  </script>
</div>