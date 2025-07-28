@props([
  'name',
  'label' => null,
  'checked' => false,
  'value' => '1'
])

<div class="form-checkbox" id="{{ 'form-field--'.$name }}">
  <input 
    type="checkbox"
    name="{{ $name }}"
    id="{{ $name }}"
    value="{{ $value }}"
    @if($checked) checked @endif
    {{ $attributes->merge(['class' => 'form-checkbox__input']) }}
  />
  
  @if($label)
    <label for="{{ $name }}" class="form-checkbox__label">
      {{ $label }}
    </label>
  @endif
  
  <x-form.error :name="$name" />
</div>