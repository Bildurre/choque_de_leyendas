@props([
  'name',
  'label',
  'options' => [],
  'selected' => [],
  'placeholder' => null,
  'multiple' => true
])

@php
  $id = $name . '-filter';
  $selectedValues = is_array($selected) ? $selected : [$selected];
@endphp

<div class="filters-select">
  <label for="{{ $id }}" class="filter-select__label">{{ $label }}</label>
  
  <select 
    id="{{ $id }}" 
    name="{{ $name }}{{ $multiple ? '[]' : '' }}"
    class="filter-select__input"
    {{ $multiple ? 'multiple' : '' }}
    data-choices
  >
    @if($placeholder && !$multiple)
      <option value="">{{ $placeholder }}</option>
    @endif
    
    @foreach($options as $value => $label)
      <option 
        value="{{ $value }}" 
        {{ in_array($value, $selectedValues) ? 'selected' : '' }}
      >
        {{ $label }}
      </option>
    @endforeach
  </select>
</div>