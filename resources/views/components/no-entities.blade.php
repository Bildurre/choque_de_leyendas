@props([
  'message' => 'No hay elementos disponibles',
  'create_route' => null,
  'create_label' => 'Crear el primer elemento'
])

<div class="no-entities">
  <p>{{ $message }}</p>
  
  @if($create_route)
    <a href="{{ $create_route }}" class="btn btn-primary">{{ $create_label }}</a>
  @endif
  
  {{ $slot }}
</div>