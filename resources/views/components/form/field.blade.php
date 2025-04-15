@props([
  'name',
  'label' => null,
  'type' => 'text',
  'required' => false,
  'value' => '',
  'placeholder' => '',
  'help' => null,
  'options' => [],
  'min' => null,
  'max' => null,
  'rows' => 4,
  'checked' => false
])

<div class="form-group {{ $type === 'checkbox' ? 'form-group-checkbox' : '' }}">
  @switch($type)
    @case('checkbox')
      <div class="checkbox-wrapper">
        <input 
          type="checkbox" 
          id="{{ $name }}" 
          name="{{ $name }}" 
          class="form-checkbox @error($name) is-invalid @enderror"
          value="1"
          {{ old($name, $checked) ? 'checked' : '' }}
          @if($required) required @endif
          {{ $attributes }}
        >
        
        @if($label)
          <label for="{{ $name }}" class="form-label checkbox-label">
            {{ $label }}
            @if($required)
              <span class="required">*</span>
            @endif
          </label>
        @endif
      </div>
      @break
      
    @default
      @if($label)
        <label for="{{ $name }}" class="form-label">
          {{ $label }}
          @if($required)
            <span class="required">*</span>
          @endif
        </label>
      @endif
      
      @switch($type)
        @case('textarea')
          <textarea 
            id="{{ $name }}" 
            name="{{ $name }}" 
            class="form-textarea @error($name) is-invalid @enderror"
            rows="{{ $rows }}"
            @if($required) required @endif
            {{ $attributes }}
          >{{ old($name, $value) }}</textarea>
          @break
          
        @case('select')
          <select 
            id="{{ $name }}" 
            name="{{ $name }}" 
            class="form-select @error($name) is-invalid @enderror"
            @if($required) required @endif
            {{ $attributes }}
          >
            <option value="">{{ $placeholder ?: 'Selecciona una opción' }}</option>
            
            @foreach($options as $optionValue => $optionLabel)
              <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                {{ $optionLabel }}
              </option>
            @endforeach
          </select>
          @break
          
        @case('color')
          <div class="color-input-group">
            <input 
              type="color" 
              id="{{ $name }}" 
              name="{{ $name }}" 
              class="form-color-input @error($name) is-invalid @enderror" 
              value="{{ old($name, $value) }}" 
              @if($required) required @endif
              {{ $attributes }}
            >
            <input 
              type="text" 
              id="{{ $name }}_text" 
              class="form-input color-text-input" 
              value="{{ old($name, $value) }}" 
              readonly
            >
          </div>
          @break
          
        @default
          <input 
            type="{{ $type }}" 
            id="{{ $name }}" 
            name="{{ $name }}" 
            value="{{ old($name, $value) }}" 
            class="form-input @error($name) is-invalid @enderror"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($min !== null) min="{{ $min }}" @endif
            @if($max !== null) max="{{ $max }}" @endif
            {{ $attributes }}
          >
      @endswitch
  @endswitch
  
  @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
  
  @if($help)
    <p class="help-text">{{ $help }}</p>
  @endif
  
  {{ $slot }}
</div>