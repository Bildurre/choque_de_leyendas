@props([
  'type' => 'text',
  'name',
  'label' => null,
  'value' => null,
  'required' => false,
  'autofocus' => false,
  'placeholder' => '',
  'autocomplete' => ''
])

<div class="form-field">
  @if($label)
    <x-form.label :for="$name" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  <input 
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $name }}"
    value="{{ $value }}"
    @if($required) required @endif
    @if($autofocus) autofocus @endif
    placeholder="{{ $placeholder }}"
    autocomplete="{{ $autocomplete }}"
    {{ $attributes->merge(['class' => 'form-input']) }}
  />
  
  <x-form.error :name="$name" />
</div>