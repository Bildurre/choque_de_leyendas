@props(['submit_label' => 'Guardar', 'cancel_route' => null, 'cancel_label' => 'Cancelar'])

<div class="form-card">
  <div class="form-section">
    {{ $slot }}
  </div>
  
  <div class="form-actions">
    <x-button type="button" variant="success">{{ $submit_label }}</x-button>
        
    @if($cancel_route)
      <x-button route="{{ $cancel_route }}" variant="danger">{{ $cancel_label }}</x-button>
    @endif
  </div>
</div>