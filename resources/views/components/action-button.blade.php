@props([
  'href' => null,
  'route' => null,
  'method' => 'GET',
  'icon' => null,
  'variant' => 'primary',
  'size' => 'md',
  'confirmMessage' => null,
  'title' => null,
  'target' => null
])

@php
  $baseClass = 'action-button';
  $variantClass = "action-button--$variant";
  $sizeClass = "action-button--$size";
  $isForm = $route && ($method !== 'GET');
  $buttonId = 'action-' . uniqid();
  $targetAttr = $target ? "target=\"$target\"" : '';
@endphp

@if($isForm)
  <form action="{{ $route }}" method="POST" class="action-button-form">
    @csrf
    @if($method !== 'POST')
      @method($method)
    @endif
    
    <button 
      type="submit" 
      id="{{ $buttonId }}"
      {{ $attributes->merge(['class' => "$baseClass $variantClass $sizeClass"]) }}
      @if($confirmMessage) data-confirm-message="{{ $confirmMessage }}" @endif
      @if($title) title="{{ $title }}" @endif
    >
      @if($icon)
        <x-icon :name="$icon" size="$size" class="action-button__icon" />
      @endif
      
      <span class="action-button__text">{{ $slot }}</span>
    </button>
  </form>
@else
  <a 
    href="{{ $href ?? $route ?? '#' }}" 
    {{ $attributes->merge(['class' => "$baseClass $variantClass $sizeClass"]) }}
    @if($confirmMessage) data-confirm-message="{{ $confirmMessage }}" @endif
    @if($title) title="{{ $title }}" @endif
    {!! $targetAttr !!}
  >
    @if($icon)
      <x-icon :name="$icon" size="$size" class="action-button__icon" />
    @endif
    
    <span class="action-button__text">{{ $slot }}</span>
  </a>
@endif