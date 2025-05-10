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
      
      <div class="cost-input__buttons">
        <button type="button" class="cost-input__button cost-input__button--red" data-dice-type="R" title="{{ __('game.cost.add_red') }}">
          <!-- Red dice icon inline SVG -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" stroke-linejoin="round" width="20px" height="20px">
            <polygon points="100,180 30,140 30,60 100,100" fill="#f15959" stroke="black" stroke-width="2"/>
            <polygon points="100,180 100,100 170,60 170,140" fill="#f15959" stroke="black" stroke-width="2"/>
            <polygon points="100,100 30,60 100,20 170,60" fill="#f15959" stroke="black" stroke-width="2"/>
          </svg>
        </button>
        
        <button type="button" class="cost-input__button cost-input__button--green" data-dice-type="G" title="{{ __('game.cost.add_green') }}">
          <!-- Green dice icon inline SVG -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" stroke-linejoin="round" width="20px" height="20px">
            <polygon points="100,180 30,140 30,60 100,100" fill="#29ab5f" stroke="black" stroke-width="2"/>
            <polygon points="100,180 100,100 170,60 170,140" fill="#29ab5f" stroke="black" stroke-width="2"/>
            <polygon points="100,100 30,60 100,20 170,60" fill="#29ab5f" stroke="black" stroke-width="2"/>
          </svg>
        </button>
        
        <button type="button" class="cost-input__button cost-input__button--blue" data-dice-type="B" title="{{ __('game.cost.add_blue') }}">
          <!-- Blue dice icon inline SVG -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" stroke-linejoin="round" width="20px" height="20px">
            <polygon points="100,180 30,140 30,60 100,100" fill="#408cfd" stroke="black" stroke-width="2"/>
            <polygon points="100,180 100,100 170,60 170,140" fill="#408cfd" stroke="black" stroke-width="2"/>
            <polygon points="100,100 30,60 100,20 170,60" fill="#408cfd" stroke="black" stroke-width="2"/>
          </svg>
        </button>
        
        <button type="button" class="cost-input__button cost-input__button--clear" data-action="clear" title="{{ __('game.cost.clear') }}">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20px" height="20px" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6L6 18M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>
    
    <div class="cost-input__preview">
      <div class="cost-input__preview-label">{{ __('game.cost.preview') }}:</div>
      <div class="cost-input__dice-container"></div>
    </div>
    
    @if($help)
      <div class="form-help">{{ $help }}</div>
    @endif
  </div>
  
  <x-form.error :name="$name" />
</div>