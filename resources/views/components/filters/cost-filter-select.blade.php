@props([
  'name',
  'label',
  'options' => [],
  'selected' => [],
  'placeholder' => null,
  'multiple' => true,
  'type' => 'cost_exact' // 'cost_exact' or 'cost_colors'
])

@php
  $id = $name . '-filter';
  $selectedValues = is_array($selected) ? $selected : [$selected];
@endphp

<div class="filter-select filter-cost-select">
  <label for="{{ $id }}" class="filter-select__label">{{ $label }}</label>
  
  <select 
    id="{{ $id }}" 
    name="{{ $name }}{{ $multiple ? '[]' : '' }}"
    class="filter-select__input"
    {{ $multiple ? 'multiple' : '' }}
    data-choices
    data-cost-filter="true"
    data-cost-filter-type="{{ $type }}"
  >
    @if($placeholder && !$multiple)
      <option value="">{{ $placeholder }}</option>
    @endif
    
    @foreach($options as $value => $label)
      <option 
        value="{{ $value }}" 
        {{ in_array($value, $selectedValues) ? 'selected' : '' }}
      >
        @if(empty($value))
          {{ $label }}
        @else
          {{ $value }}
        @endif
      </option>
    @endforeach
  </select>
</div>