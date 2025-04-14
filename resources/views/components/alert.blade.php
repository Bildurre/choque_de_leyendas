@props(['type' => 'info'])

@php
  $alertClass = 'alert alert--' . $type;
@endphp

<div {{ $attributes->merge(['class' => $alertClass]) }}>
  {{ $slot }}
  
  <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
  
</div>