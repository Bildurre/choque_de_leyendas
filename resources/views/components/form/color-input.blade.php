@props([
  'name' => 'color',
  'value' => '#000000',
  'required' => false
])

<div class="color-input-group">
  <input 
    type="color" 
    id="{{ $name }}" 
    name="{{ $name }}" 
    class="form-color-input @error($name) is-invalid @enderror" 
    value="{{ old($name, $value) }}" 
    @if($required) required @endif
    {{ $attributes }}
  >
  <input 
    type="text" 
    id="{{ $name }}_text" 
    class="form-input color-text-input" 
    value="{{ old($name, $value) }}" 
    readonly
  >
</div>