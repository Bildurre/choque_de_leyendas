@props([
  'name',
  'value' => '',
  'required' => false,
  'placeholder' => 'Selecciona una opción',
  'options' => []
])

<select 
  id="{{ $name }}" 
  name="{{ $name }}" 
  class="form-input @error($name) is-invalid @enderror"
  @if($required) required @endif
  {{ $attributes }}
>
  <option value="">{{ $placeholder }}</option>
  
  @foreach($options as $optionValue => $optionLabel)
    <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
      {{ $optionLabel }}
    </option>
  @endforeach
</select>