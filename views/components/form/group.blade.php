@props(['name', 'label', 'required' => false])

<div class="form-group">
  <label for="{{ $name }}" class="form-label">
    {{ $label }}
    @if($required)
      <span class="required">*</span>
    @endif
  </label>
  
  {{ $slot }}
  
  @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>