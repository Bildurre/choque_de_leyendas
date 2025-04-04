@props([
  'title',
  'subtitle' => '',
  'back_route' => null,
  'back_label' => 'Volver al listado',
  'create_route' => null,
  'create_label' => '+ Nuevo'
])

<div class="header-actions-bar">
  <div class="left-actions">
    <h1>{{ $title }}</h1>
    @if($subtitle)
      <p>{{ $subtitle }}</p>
    @endif
  </div>
  <div class="right-actions">
    @if($back_route)
      <a href="{{ $back_route }}" class="btn btn-secondary">
        <span>{{ $back_label }}</span>
      </a>
    @endif
    
    @if($create_route)
      <a href="{{ $create_route }}" class="btn btn-primary">
        <span>{{ $create_label }}</span>
      </a>
    @endif
    
    {{ $slot ?? '' }}
  </div>
</div>