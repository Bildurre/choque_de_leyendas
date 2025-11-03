{{-- resources/views/components/form/select.blade.php --}}

@props([
  'name',
  'label' => null,
  'options' => [],
  'selected' => null,
  'placeholder' => null,
  'required' => false,
  'multiple' => false,
  'size' => null,
  'useChoices' => null, // Explicitly enable/disable Choices.js
  'searchable' => true,
])

@php
  // Auto-enable Choices.js for multiple selects unless explicitly disabled
  $shouldUseChoices = $useChoices ?? $multiple;
@endphp

<div class="form-field" id="{{ 'form-field--'.$name }}">
  @if($label)
    <x-form.label :for="$name" :required="$required">{{ $label }}</x-form.label>
  @endif

  <select 
    name="{{ $name }}{{ $multiple ? '[]' : '' }}"
    id="{{ $name }}"
    class="form-select"
    {{ $required ? 'required' : '' }}
    {{ $multiple ? 'multiple' : '' }}
    {{ $size ? "size=$size" : '' }}
    {{ $shouldUseChoices ? 'data-choices' : '' }}
    @if($placeholder)
      data-placeholder="{{ $placeholder }}"
    @endif
    {{ $attributes }}
  >
    @if($placeholder && !$multiple)
      <option value="" {{ is_null($selected) ? 'selected' : '' }} {{ $required ? 'disabled' : '' }}>
        {{ $placeholder }}
      </option>
    @endif
    
    @foreach($options as $value => $label)
      @php
        $isSelected = false;
        if ($multiple && is_array($selected)) {
          $isSelected = in_array($value, $selected);
        } else {
          $isSelected = $selected == $value;
        }
      @endphp
      <option value="{{ $value }}" {{ $isSelected ? 'selected' : '' }}>
        {{ $label }}
      </option>
    @endforeach
  </select>

  <x-form.error :name="$name" />
</div>