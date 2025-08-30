@props([
  'name',
  'id',
  'value',
  'label' => null,
  'checked' => false,
  'required' => false
])

<div class="form-radio">
  <input
    type="radio"
    name="{{ $name }}"
    id="{{ $id }}"
    value="{{ $value }}"
    class="form-radio__input"
    {{ $checked ? 'checked' : '' }}
    {{ $required ? 'required' : '' }}
    {{ $attributes }}
  />
  @if($label)
    <label for="{{ $id }}" class="form-radio__label">
      {{ $label }}
    </label>
  @endif
</div>