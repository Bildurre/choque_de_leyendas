@props([
  'type' => 'info',
  'dismissible' => true
])

@php
  $baseClass = 'notification';
  $typeClass = "notification--$type";
@endphp

<div {{ $attributes->merge(['class' => "$baseClass $typeClass"]) }} role="alert">
  <div class="notification__content">
    <div class="notification__icon">
      @if($type === 'success')
        <x-icon name="check-circle" />
      @elseif($type === 'error')
        <x-icon name="x-circle" />
      @elseif($type === 'warning')
        <x-icon name="alert-triangle" />
      @else
        <x-icon name="info" />
      @endif
    </div>
    
    <div class="notification__message">
      {{ $slot }}
    </div>
  </div>
  
  @if($dismissible)
    <button type="button" class="notification__close" aria-label="Cerrar notificación">
      <x-icon name="x" size="sm" />
    </button>
  @endif
</div>