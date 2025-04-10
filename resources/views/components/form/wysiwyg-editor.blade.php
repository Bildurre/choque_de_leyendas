@props([
  'name',
  'value' => '',
  'required' => false,
  'rows' => 10,
  'placeholder' => '',
  'imageList' => null
])

@php
  if ($imageList === null) {
    $imageList = app(App\Services\WysiwygImageService::class)->getAvailableImages();
  }
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
</div>