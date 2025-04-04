@props([
  'type' => 'text',
  'name',
  'value' => '',
  'required' => false,
  'placeholder' => '',
  'min' => null,
  'max' => null
])

<input 
  type="{{ $type }}" 
  id="{{ $name }}" 
  name="{{ $name }}" 
  value="{{ old($name, $value) }}" 
  class="form-input @error($name) is-invalid @enderror"
  placeholder="{{ $placeholder }}"
  @if($required) required @endif
  @if($min !== null) min="{{ $min }}" @endif
  @if($max !== null) max="{{ $max }}" @endif
  {{ $attributes }}
>