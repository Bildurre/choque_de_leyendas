@props([
  'variant' => null,
  'route' => '#',
  'icon' => null,
  'method' => null,
  'confirm' => false,
  'confirmMessage' => '¿Estás seguro?',
  'confirmAttribute' => null,
  'confirmValue' => '',
  'size' => 'md'
])

@php
  $buttonClass = "action-btn action-btn--{$variant}";
  if ($size !== 'md') {
    $buttonClass .= " action-btn--{$size}";
  }
@endphp

@if($variant == 'delete')
  <form action="{{ $route }}" method="POST" class="action-btn__form">
    @csrf
    @method($method)
    <button 
      class="{{ $buttonClass }}"
      type="submit"
      @if($confirm && $confirmAttribute)
        data-{{ $confirmAttribute }}="{{ $confirmValue }}"
      @endif
    >
      <x-core.icon :name="$icon" />
      {{ $slot }}
    </button>
  </form>
@elseif ($variant == 'toggle')
  <button 
    type="button"
    class="{{ $buttonClass }}"
    data-toggle="entity-details"
  >
    <x-core.icon name="chevron-down" class="chevron-down" />
    <x-core.icon name="chevron-up" class="chevron-up" />
  </button>
@else
  <a 
    href="{{ $route }}" 
    class="{{ $buttonClass }}"
  >
    <x-core.icon :name="$icon" />
    {{ $slot }}
  </a>
@endif