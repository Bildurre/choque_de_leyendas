@props([
  'name',
  'label' => null,
  'value' => null,
  'required' => false
])

<div class="form-field">
  @if($label)
    <x-form.label :for="$name" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  <div class="color-picker">
    <div class="color-picker__input-container">
      <input 
        type="text" 
        name="{{ $name }}" 
        id="{{ $name }}"
        value="{{ $value }}"
        class="form-input color-picker__text"
        placeholder="#RRGGBB"
        {{ $required ? 'required' : '' }}
        {{ $attributes }}
      />
      
      <input 
        type="color" 
        id="{{ $name }}_picker"
        value="{{ $value ?: '#ffffff' }}"
        class="color-picker__selector"
        aria-label="{{ $label ?: __('components.form.color_selector') }}"
      />
    </div>
  </div>
  
  <x-form.error :name="$name" />
</div>