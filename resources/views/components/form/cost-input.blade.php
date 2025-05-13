@props([
  'name',
  'label' => null,
  'value' => null,
  'required' => false,
  'help' => null
])

<div class="form-field">
  @if($label)
    <x-form.label :for="$name" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  <div class="cost-input">
    <div class="cost-input__controls">
      <!-- Input de costo a la izquierda -->
      <div class="cost-input__input-container">
        <input 
          type="text"
          name="{{ $name }}"
          id="{{ $name }}"
          value="{{ $value }}"
          {{ $required ? 'required' : '' }}
          class="form-input cost-input__field"
          maxlength="5"
          {{ $attributes }}
        />
      </div>
      
      <!-- Vista previa a la derecha -->
      <div class="cost-input__preview">
        <div class="cost-input__preview-label">{{ __('game.cost.preview') }}:</div>
        <div class="cost-input__dice-container"></div>
      </div>
    </div>
    
    <!-- Botones debajo -->
    <div class="cost-input__buttons">
      <button type="button" class="cost-input__button cost-input__button--red" data-dice-type="R" title="{{ __('game.cost.add_red') }}">
        <x-icon-dice type="red" size="sm" />
      </button>
      
      <button type="button" class="cost-input__button cost-input__button--green" data-dice-type="G" title="{{ __('game.cost.add_green') }}">
        <x-icon-dice type="green" size="sm" />
      </button>
      
      <button type="button" class="cost-input__button cost-input__button--blue" data-dice-type="B" title="{{ __('game.cost.add_blue') }}">
        <x-icon-dice type="blue" size="sm" />
      </button>
      
      <button type="button" class="cost-input__button cost-input__button--clear" data-action="clear" title="{{ __('game.cost.clear') }}">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20px" height="20px" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 6L6 18M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    
    @if($help)
      <div class="form-help">{{ $help }}</div>
    @endif
  </div>
  
  <x-form.error :name="$name" />
</div>