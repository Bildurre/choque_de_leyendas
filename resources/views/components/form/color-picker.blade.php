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
        aria-label="{{ $label ?: __('form.color_selector') }}"
      />
    </div>
  </div>
  
  <x-form.error :name="$name" />
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const textInput = document.getElementById('{{ $name }}');
    const colorInput = document.getElementById('{{ $name }}_picker');
    
    if (textInput && colorInput) {
      // Sync color picker with text input
      textInput.addEventListener('input', function() {
        let color = this.value;
        
        // Add # if missing
        if (color && !color.startsWith('#')) {
          color = '#' + color;
        }
        
        // Update color picker
        if (/^#([0-9A-F]{3}){1,2}$/i.test(color)) {
          colorInput.value = color;
        }
      });
      
      // Sync text input with color picker
      colorInput.addEventListener('input', function() {
        textInput.value = this.value;
      });
    }
  });
</script>
@endpush