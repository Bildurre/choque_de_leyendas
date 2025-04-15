@props([
  'name',
  'label' => null,
  'required' => false,
  'value' => '',
  'help' => null,
  'rows' => 5
])

<x-form.group>
  @if($label)
    <x-form.label :for="$name" :required="$required">
      {{ $label }}
    </x-form.label>
  @endif
  
  <textarea 
    id="{{ $name }}" 
    name="{{ $name }}" 
    class="form-textarea @error($name) is-invalid @enderror"
    rows="{{ $rows }}"
    @if($required) required @endif
    {{ $attributes }}
  >{{ old($name, $value) }}</textarea>
  
  <x-form.error :name="$name" />
  
  <x-form.help :text="$help" />
  
</x-form.group>
