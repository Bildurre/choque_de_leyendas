@props([
  'name',
  'label' => null,
  'required' => false,
  'value' => '#000000',
  'help' => null
])

<x-form.group>
  @if($label)
    <x-form.label :for="$name" :required="$required">
      {{ $label }}
    </x-form.label>
  @endif
  
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
  
  <x-form.error :name="$name" />
  
  <x-form.help :text="$help" />
  
</x-form.group>