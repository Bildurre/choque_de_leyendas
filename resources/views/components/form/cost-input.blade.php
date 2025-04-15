@props([
  'name' => 'cost',
  'label' => null,
  'value' => '',
  'required' => false,
  'placeholder' => 'Ej: RRG (Rojo, Rojo, Verde)',
  'help' => 'Usar R (Rojo), G (Verde), B (Azul). Máximo 5 dados.'
])

<x-form.group>
  @if($label)
    <x-form.label :for="$name" :required="$required">
      {{ $label }}
    </x-form.label>
  @endif
  
  <div class="cost-input-container">
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
      {{-- El contenido será manejado por JS --}}
    </div>
    
    <div class="cost-buttons">
      <button type="button" class="cost-button" data-cost="R">R</button>
      <button type="button" class="cost-button" data-cost="G">G</button>
      <button type="button" class="cost-button" data-cost="B">B</button>
      <button type="button" class="cost-button-clear">Limpiar</button>
    </div>
  </div>
  
  <x-form.error :name="$name" />
  
  <x-form.help :text="$help" />

</x-form.group>