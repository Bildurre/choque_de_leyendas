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
      <button type="submit" class="btn btn--primary">
        {{ $submit_label }}
      </button>
      
      @if($cancel_route)
        <a href="{{ $cancel_route }}" class="btn btn--secondary">
          {{ $cancel_label }}
        </a>
      @endif
      
      @if(isset($buttons))
        {{ $buttons }}
      @endif
    </div>
  </div>
</div>