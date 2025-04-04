@props([
  'name',
  'value' => '',
  'required' => false,
  'rows' => 4
])

<textarea 
  id="{{ $name }}" 
  name="{{ $name }}" 
  class="form-textarea @error($name) is-invalid @enderror"
  rows="{{ $rows }}"
  @if($required) required @endif
  {{ $attributes }}
>{{ old($name, $value) }}</textarea>