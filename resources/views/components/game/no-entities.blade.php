@props([
  'message' => 'No hay elementos disponibles',
  'createRoute' => null,
  'createLabel' => 'Crear el primer elemento'
])

<div class="no-entities">
  <p>{{ $message }}</p>
  
  @if($createRoute)
    <x-core.button route="{{ $createRoute }}">
      {{ $createLabel }}
    </x-core.button>
  @endif
</div>