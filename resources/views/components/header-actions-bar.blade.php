@props([
  'title',
  'subtitle' => '',
  'backRoute' => null,
  'backLabel' => 'Volver al listado',
  'createRoute' => null,
  'createLabel' => '+ Nuevo'
])

<div class="header-actions-bar">
  <div class="left-actions">
    <h1>{{ $title }}</h1>
    @if($subtitle)
      <p>{{ $subtitle }}</p>
    @endif
  </div>
  <div class="right-actions">
    @if($createRoute)
      <x-button route="{{ $createRoute }}">{{ $createLabel }}</x-button>
    @endif
    
    @if($backRoute)
      <x-button route="{{ $backRoute }}">{{ $backLabel }}</x-button>
    @endif
  </div>
</div>