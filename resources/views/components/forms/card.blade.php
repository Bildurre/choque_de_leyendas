@props(['submit_label' => 'Guardar', 'cancel_route' => null, 'cancel_label' => 'Cancelar'])

<div class="form-card">
  <div class="form-section">
    {{ $slot }}
  </div>
  
  <div class="form-actions">
    <button type="submit" class="btn btn__success">{{ $submit_label }}</button>
    
    @if($cancel_route)
      <a href="{{ $cancel_route }}" class="btn btn__danger">{{ $cancel_label }}</a>
    @endif
  </div>
</div>