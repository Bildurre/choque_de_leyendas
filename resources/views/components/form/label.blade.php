@props([
  'for',
  'required' => false,
  'isCheckbox' => false
])

<label for="{{ $for }}" class="form-label {{ $isCheckbox ? 'checkbox-label' : '' }}">
  {{ $slot }}
  @if($required)
    <span class="required">*</span>
  @endif
</label>