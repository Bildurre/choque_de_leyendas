@props([
  'hasCheckbox' => false,
  'hidden' => false,
  'hiddenCondition' => null
])

@php
  $isHidden = $hidden || ($hiddenCondition !== null && $hiddenCondition);
  $style = $isHidden ? 'display: none;' : '';
@endphp

<div class="form-group {{ $hasCheckbox ? 'form-group-checkbox' : '' }}" style="{{ $style }}">
  {{ $slot }}
</div>