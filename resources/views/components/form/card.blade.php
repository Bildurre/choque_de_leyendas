@props([
  'submit_label' => 'Submit',
  'cancel_route' => null,
  'cancel_label' => 'Cancel'
])

<div {{ $attributes->merge(['class' => 'form-card']) }}>
  <div class="form-card__content">
    @if(isset($header))
      <div class="form-card__header">
        {{ $header }}
      </div>
    @endif

    <div class="form-card__body">
      {{ $slot }}
    </div>

    <div class="form-card__footer">
      <x-button 
        type="submit" 
        variant="primary"
      >
        {{ $submit_label }}
      </x-button>
      
      @if($cancel_route)
        <x-button-link 
          :href="$cancel_route" 
          variant="secondary"
        >
          {{ $cancel_label }}
        </x-button-link>
      @endif
      
      @if(isset($buttons))
        {{ $buttons }}
      @endif
    </div>
  </div>
</div>