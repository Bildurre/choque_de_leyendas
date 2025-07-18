@props([
  'type' => 'text',
  'name',
  'label' => null,
  'value' => null,
  'required' => false,
  'autofocus' => false,
  'placeholder' => '',
  'autocomplete' => ''
])

<div class="form-field">
  @if($label)
    <x-form.label :for="$name" :required="$required">{{ $label }}</x-form.label>
  @endif
  
  @if($type === 'number')
    <div class="number-input-wrapper">
      <button 
        type="button" 
        class="number-input__button number-input__button--decrement"
        data-action="decrement"
        aria-label="{{ __('form.decrease') }}"
      >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
      </button>
      
      <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        @if($required) required @endif
        @if($autofocus) autofocus @endif
        placeholder="{{ $placeholder }}"
        autocomplete="{{ $autocomplete }}"
        {{ $attributes->merge(['class' => 'form-input']) }}
      />
      
      <button 
        type="button" 
        class="number-input__button number-input__button--increment"
        data-action="increment"
        aria-label="{{ __('form.increase') }}"
      >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"></line>
          <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
      </button>
    </div>
  @else
    <input 
      type="{{ $type }}"
      name="{{ $name }}"
      id="{{ $name }}"
      value="{{ $value }}"
      @if($required) required @endif
      @if($autofocus) autofocus @endif
      placeholder="{{ $placeholder }}"
      autocomplete="{{ $autocomplete }}"
      {{ $attributes->merge(['class' => 'form-input']) }}
    />
  @endif
  
  <x-form.error :name="$name" />
</div>