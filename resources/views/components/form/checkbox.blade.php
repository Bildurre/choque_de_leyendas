@props([
  'name',
  'label' => null,
  'required' => false,
  'checked' => false,
  'help' => null
])

<x-form.group :hasCheckbox="true">
  <div class="checkbox-wrapper">
    <input 
      type="checkbox" 
      id="{{ $name }}" 
      name="{{ $name }}" 
      class="form-checkbox @error($name) is-invalid @enderror"
      value="1"
      {{ old($name, $checked) ? 'checked' : '' }}
      @if($required) required @endif
      {{ $attributes }}
    >
    
    @if($label)
      <x-form.label :for="$name" :required="$required" :isCheckbox="true">
        {{ $label }}
      </x-form.label>
    @endif
  </div>
  
  <x-form.error :name="$name" />
  
  <x-form.help :text="$help" />
  
  {{ $slot }}
</x-form.group>