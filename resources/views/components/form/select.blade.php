@props([
  'name',
  'label' => null,
  'required' => false,
  'value' => '',
  'placeholder' => '',
  'help' => null,
  'options' => []
])

<x-form.group>
  @if($label)
    <x-form.label :for="$name" :required="$required">
      {{ $label }}
    </x-form.label>
  @endif
  
  <select 
    id="{{ $name }}" 
    name="{{ $name }}" 
    class="form-select @error($name) is-invalid @enderror"
    @if($required) required @endif
    {{ $attributes }}
  >
    <option value="">{{ $placeholder ?: 'Selecciona una opción' }}</option>
    
    @foreach($options as $optionValue => $optionLabel)
      <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
        {{ $optionLabel }}
      </option>
    @endforeach
  </select>
  
  <x-form.error :name="$name" />
  
  <x-form.help :text="$help" />

</x-form.group>