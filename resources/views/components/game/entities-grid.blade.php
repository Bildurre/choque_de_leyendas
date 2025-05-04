@props([
  'emptyMessage' => 'No hay elementos disponibles', 
  'createRoute' => null, 
  'createLabel' => 'Crear el primer elemento',
  'columns' => false
])

<div {{ $attributes->merge(['class' => 'entities-grid' . ($columns ? ' entities-grid--columns' : '')]) }}>  
  @if(!$slot->isEmpty())
    {{ $slot }}
  @else
    <x-game.no-entities 
      :message="$emptyMessage"
      :createRoute="$createRoute"
      :createLabel="$createLabel"
    />
  @endif
</div>