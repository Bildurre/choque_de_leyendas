@props([
  'type' => 'default',
  'route' => '#',
  'title' => '',
  'icon' => null,
  'method' => null,
  'confirm' => false,
  'confirmMessage' => '¿Estás seguro?',
  'confirmAttribute' => null,
  'confirmValue' => '',
  'size' => 'md'
])

@php
  $buttonClass = "action-btn {$type}-btn";
  if ($size !== 'md') {
    $buttonClass .= " action-btn-{$size}";
  }
  
  // Determinar si es formulario o enlace
  $isForm = $method && in_array(strtoupper($method), ['DELETE', 'PUT', 'PATCH']);
@endphp

@if($isForm)
  <form action="{{ $route }}" method="POST" class="action-btn-form delete-form">
    @csrf
    @method($method)
    <button 
      type="submit" 
      class="{{ $buttonClass }}" 
      title="{{ $title }}"
      @if($confirm && $confirmAttribute)
        data-{{ $confirmAttribute }}="{{ $confirmValue }}"
      @endif
    >
      <x-common.icon :name="$icon" />
    </button>
  </form>
@else
  <a 
    href="{{ $route }}" 
    class="{{ $buttonClass }}" 
    title="{{ $title }}"
  >
    <x-common.icon :name="$icon" />
  </a>
@endif