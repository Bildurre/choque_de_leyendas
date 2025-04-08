<!-- resources/views/components/entities-grid.blade.php -->
@props([
  'empty_message' => 'No hay elementos disponibles', 
  'create_route' => null, 
  'create_label' => 'Crear el primer elemento'
])

<div {{ $attributes->merge(['class' => 'entities-grid']) }}>
  @if(!$slot->isEmpty())
    {{ $slot }}
  @else
    <x-common.no-entities 
      :message="$empty_message"
      :create_route="$create_route"
      :create_label="$create_label"
    />
  @endif
</div>