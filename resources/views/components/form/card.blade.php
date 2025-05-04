@props(['submit_label' => 'Guardar', 'cancel_route' => null, 'cancel_label' => 'Cancelar'])

<div class="form-card">
  <div class="form-section">
    {{ $slot }}
  </div>
  
  <div class="form-actions">
    <x-core.button type="button" variant="success">{{ $submit_label }}</x-core.button>
        
    @if($cancel_route)
      <x-core.button route="{{ $cancel_route }}" variant="danger">{{ $cancel_label }}</x-core.button>
    @endif
  </div>
</div>