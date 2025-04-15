@props([
  'hasCheckbox' => false
])

<div class="form-group {{ $hasCheckbox ? 'form-group-checkbox' : '' }}">
  {{ $slot }}
</div>