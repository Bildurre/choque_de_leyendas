@props([
  'name',
  'label' => null,
  'type' => 'text',
  'required' => false,
  'value' => '',
  'placeholder' => '',
  'help' => null,
  'min' => null,
  'max' => null
])

@php
  $textTypes = ['text', 'password', 'email', 'search', 'tel', 'url'];
  $isTextType = in_array($type, $textTypes);
@endphp

<x-form.group>
  @if($label)
    <x-form.label :for="$name" :required="$required">
      {{ $label }}
    </x-form.label>
  @endif
  
  <input 
    type="{{ $type }}" 
    id="{{ $name }}" 
    name="{{ $name }}" 
    value="{{ old($name, $value) }}" 
    class="form-input @error($name) is-invalid @enderror"
    placeholder="{{ $placeholder }}"
    @if($required) required @endif
    @if($min !== null) {{ $isTextType ? "minlength=$min" : "min=$min" }} @endif
    @if($max !== null) {{ $isTextType ? "maxlength=$max" : "max=$max" }} @endif
    {{ $attributes }}
  >
  
  <x-form.error :name="$name" />
  
  <x-form.help :text="$help" />
  
</x-form.group>