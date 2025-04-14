@props([
  'name' => 'cost',
  'value' => '',
  'required' => false,
  'placeholder' => 'Ej: RRG (Rojo, Rojo, Verde)',
  'helpText' => 'Usar R (Rojo), G (Verde), B (Azul). Máximo 5 dados.',
  'label' => 'Coste de Activación'
])

<div class="cost-input-container">
  <label for="{{ $name }}" class="form-label">{{ $label }}</label>
  
  <div class="cost-input-group">
    <input 
      type="text" 
      id="{{ $name }}" 
      name="{{ $name }}" 
      value="{{ old($name, $value) }}" 
      class="form-input cost-input @error($name) is-invalid @enderror"
      placeholder="{{ $placeholder }}"
      @if($required) required @endif
      {{ $attributes }}
    >
    
    <div class="cost-preview" id="{{ $name }}-preview">
      @if($value)
        <x-widgets.cost-display :cost="$value" />
      @endif
    </div>
  </div>
  
  @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
  
  <div class="cost-help">
    <span class="form-text">{{ $helpText }}</span>
  </div>
  
  <div class="cost-buttons">
    <button type="button" class="btn btn-sm btn-secondary cost-button" data-cost="R">R</button>
    <button type="button" class="btn btn-sm btn-secondary cost-button" data-cost="G">G</button>
    <button type="button" class="btn btn-sm btn-secondary cost-button" data-cost="B">B</button>
    <button type="button" class="btn btn-sm btn-secondary cost-button-clear">Limpiar</button>
  </div>
</div>