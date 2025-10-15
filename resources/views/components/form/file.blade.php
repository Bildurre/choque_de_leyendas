@props([
  'name',
  'label' => null,
  'required' => false,
  'accept' => null
])

<div class="form-field" id="{{ 'form-field--'.$name }}">
  @if($label)
    <x-form.label :for="$name" :required="$required">{{ $label }}</x-form.label>
  @endif

  <input 
    type="file"
    name="{{ $name }}"
    id="{{ $name }}"
    class="form-file"
    {{ $required ? 'required' : '' }}
    {{ $accept ? "accept=$accept" : '' }}
    {{ $attributes }}
  >

  <x-form.error :name="$name" />
</div>