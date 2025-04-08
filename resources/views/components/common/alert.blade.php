@props(['type' => 'info', 'dismissible' => true])

@php
  $alertClass = 'alert alert-' . $type;
  if ($dismissible) $alertClass .= ' alert-dismissible';
@endphp

<div {{ $attributes->merge(['class' => $alertClass]) }}>
  {{ $slot }}
  
  @if($dismissible)
    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
  @endif
</div>